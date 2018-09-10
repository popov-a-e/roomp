<?php

namespace Roomp\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Roomp\BusinessDeveloper;

class MigrateViews extends Command {
  protected $signature = 'migrate:views';

  protected $description = 'Migrating existing views';

  public function handle() {
    DB::statement('DROP VIEW IF EXISTS analytics__reservations');

    DB::statement("
      CREATE OR REPLACE VIEW analytics__reservations AS
      SELECT
        res_main.id AS id,
        res_main.code AS reservation_id,
        res_main.from AS check_in,
        DATE (res_main.till + INTERVAL '1' DAY) AS check_out,
        DATE (res_main.till + INTERVAL '1' DAY) - DATE (res_main.from) AS nights,
        (DATE (res_main.till + INTERVAL '1' DAY) - DATE (res_main.from)) * o.rooms AS rooms_nights,
        o.rooms AS rooms,
        o.rate AS rate,
        o.guest_number AS guest_number,
        res_main.guest_name AS guest_name,
        hotels.ru AS hotel_name,
        hotels.id AS hotel_id,
        cities.ru AS city,
        channels.name as channel,
        statuses.online as is_online,
        CASE
          WHEN res_main.promo_code_id IS NULL THEN o.price
          ELSE (
            SELECT 
              CASE 
                WHEN type = '%' THEN o.price - floor(o.price * LEAST(100, splitted.value::int) / 100)::int
                WHEN type = 'RUB' THEN o.price - splitted.value::int
              END
            FROM 
            (SELECT
              regexp_split_to_array[1] AS value, regexp_split_to_array[2] AS type 
            FROM  
              regexp_split_to_array(promo_codes.discount, '\\s') 
            LIMIT 1) 
          splitted)
        END as price,
        CASE 
          WHEN res_main.noshow = TRUE THEN 'noshow'
          WHEN DATE(NOW()) - DATE(res_main.till) >= 0 AND (statuses.active) THEN 'fulfilled'
          ELSE statuses.name
        END as status,
        res_main.created_at as created_at,
        (CASE 
          WHEN channels.name = 'Booking' THEN 
            ( 
              SELECT
              CASE WHEN wubook__connections.premium_program THEN 0.18 ELSE 0.15 END
            )
          WHEN channels.name = 'Roomp' THEN 0  
          ELSE (
            CASE 
              WHEN res_count.count / room_num.number / (SELECT DATE_PART('days', DATE_TRUNC('month', res_main.till) + '1 MONTH'::INTERVAL - '1 DAY'::INTERVAL)) < 0.1 THEN 0.145
              WHEN res_count.count / room_num.number / (SELECT DATE_PART('days', DATE_TRUNC('month', res_main.till) + '1 MONTH'::INTERVAL - '1 DAY'::INTERVAL)) < 0.15 THEN 0.18
              WHEN res_count.count / room_num.number / (SELECT DATE_PART('days', DATE_TRUNC('month', res_main.till) + '1 MONTH'::INTERVAL - '1 DAY'::INTERVAL)) < 0.2 THEN 0.20
              WHEN res_count.count / room_num.number / (SELECT DATE_PART('days', DATE_TRUNC('month', res_main.till) + '1 MONTH'::INTERVAL - '1 DAY'::INTERVAL)) < 0.25 THEN 0.22
              WHEN res_count.count / room_num.number / (SELECT DATE_PART('days', DATE_TRUNC('month', res_main.till) + '1 MONTH'::INTERVAL - '1 DAY'::INTERVAL)) < 0.3 THEN 0.24
              ELSE 0.26
            END  
          )
        END)::FLOAT * 1.0 as commission
      FROM reservations res_main
      LEFT JOIN (
        SELECT 
          COUNT(occupancy.id) AS rooms, 
          reservation_id, 
          CEIL(SUM(coalesce((SELECT SUM(value::TEXT::FLOAT) AS sum FROM json_array_elements(occupancy.rates))::FLOAT, 0))) AS rate,
          CEIL(SUM(coalesce((SELECT SUM(value::TEXT::FLOAT) AS price FROM json_array_elements(occupancy.prices))::FLOAT, 0))) AS price,
          SUM(adults) + SUM(children) + SUM(infants) as guest_number
          FROM occupancy
          GROUP BY occupancy.reservation_id
        ) o
      ON res_main.id = o.reservation_id
      LEFT JOIN status_log AS stl_main
        ON stl_main.reservation_id = res_main.id 
        AND stl_main.id = (
          SELECT 
            MAX(id) 
          FROM status_log stl 
          WHERE stl.reservation_id = stl_main.reservation_id
        )
      LEFT JOIN statuses ON stl_main.status_id = statuses.id  
      LEFT JOIN channels ON res_main.channel_id = channels.id
      LEFT JOIN hotels ON hotels.id = res_main.hotel_id
      LEFT JOIN cities ON hotels.city_id = cities.id
      LEFT JOIN policies ON hotels.id = policies.hotel_id
      LEFT JOIN wubook__connections ON hotels.id = wubook__connections.hotel_id
      LEFT JOIN promo_codes ON promo_codes.id = res_main.promo_code_id
      LEFT JOIN (
            SELECT
              SUM((DATE (res_made.till + INTERVAL '1' DAY) - DATE (res_made.from)) * ocr.count) as count, 
              res_made.hotel_id as hotel_id, 
              extract(month from res_made.till) as _month,
              extract(year from res_made.till) as _year
            FROM reservations res_made
            LEFT JOIN channels c ON c.id = res_made.channel_id
            LEFT JOIN (SELECT COUNT(*) as count, reservation_id FROM occupancy GROUP BY occupancy.reservation_id) ocr ON ocr.reservation_id = res_made.id
            WHERE c.name = 'Ostrovok'
            GROUP BY res_made.hotel_id, _month, _year
          ) res_count ON 
          res_count.hotel_id = hotels.id AND 
          res_count._month = extract(month from res_main.till) AND
          res_count._year = extract(year from res_main.till)
      LEFT JOIN 
        (
          SELECT SUM(number) as number, hotel_id from rooms GROUP BY hotel_id
          ) room_num ON room_num.hotel_id = hotels.id
      WHERE hotels.deleted_at IS NULL;
    ");

    DB::statement('GRANT SELECT ON analytics__reservations TO analytics');

    DB::statement('DROP VIEW IF EXISTS analytics__hotels');

    DB::statement("
      CREATE OR REPLACE VIEW analytics__hotels AS
      SELECT 
        hotels.id as hotel_id,
        hotels.ru as hotel_name,
        hotels.goody_bags_left AS goody_bags_left,
        cities.ru as city,        
        SUM(rooms.number) as room_number,
        (NOT hotels.disabled) as active
      FROM hotels
      LEFT JOIN cities ON hotels.city_id = cities.id
      LEFT JOIN rooms ON rooms.hotel_id = hotels.id  
      WHERE hotels.deleted_at IS NULL
      GROUP BY hotels.id, cities.id  
    ");

    DB::statement('GRANT SELECT ON analytics__hotels TO analytics');

    DB::statement("
      CREATE OR REPLACE VIEW analytics__rooms AS
      SELECT 
        occupancies.id as occ_id,
        rooms.name as room_name,
        room_classes.ru as room_class,
        allotments.ru as allotment,
        occupancies.reservation_id as reservation_id,
        occupancies.adults AS adults,
        occupancies.children as children,   
        occupancies.infants as infants,      
        occupancies.tariff as tariff,
        occupancies.rates as daily_rates,
        occupancies.prices as daily_prices,
        occupancies.breakfast_included as breakfast_included,
        occupancies.is_nr as is_nonrefundable
      FROM occupancy as occupancies
      LEFT JOIN rooms ON occupancies.room_id = rooms.id  
      LEFT JOIN room_classes ON rooms.room_class_id = room_classes.id  
      LEFT JOIN allotments ON occupancies.allotment_id = allotments.id
    ");

    DB::statement('GRANT SELECT ON analytics__hotels TO analytics');
  }
}
