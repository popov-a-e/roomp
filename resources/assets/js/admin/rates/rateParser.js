"use strict";
const dateParser = dateString => {
  const values = dateString.split(/[.\/]/).map(Number);

  if (values.some(isNaN)) return false;

  if (values.length === 1) {
    return {
      date: null,
      month: values[0] - 1,
      year: null
    }
  }

  if (values.length === 3) {
    if (values[2] < 100) values[2] += 2000;

    return {
      date: values[0],
      month: values[1] - 1,
      year: values[2]
    }
  }

  if (values[1] > 12) {
    if (values[1] < 100) values[1] += 2000;

    return {
      date: null,
      month: values[0] - 1,
      year: values[1]
    }
  }

  return {
    date: values[0],
    month: values[1] - 1,
    year: null
  }
};

const datesParser = dates => {
  if (dates[0] === "") return date => false;

  const dateStart = dateParser(dates[0]);

  if (dates.length === 1) {
    return date => {
      return dateStart && (dateStart.date === null || date.getDate() === dateStart.date) && (dateStart.month === null || date.getMonth() === dateStart.month) && (dateStart.year === null || dateStart.year === date.getFullYear());
    };
  }

  const dateEnd = dateParser(dates[1]);


  return date => {
    date.setHours(0, 0, 0, 0);
    const from = new Date(dateStart.year || date.getFullYear(), dateStart.month === null ? date.getMonth() : dateStart.month, dateStart.date || date.getDate(), 0, 0, 0, 0);
    const till = new Date(dateEnd.year || date.getFullYear(), dateStart.month === null ? date.getMonth() : dateEnd.month, dateEnd.date || date.getDate(), 0, 0, 0, 0);

    if (till < from) return date >= from || date <= till;

    return date >= from && date <= till;
  };
};

const ruleParser = ruleString => {
  const workdays = ruleString.indexOf('d') >= 0;
  const weekend = ruleString.indexOf('w') >= 0;

  const dates = ruleString.replace(/[dw]/, '').split(/-/);

  return date => {
    const day = date.getDay();
    if (workdays && (day === 0 || day === 6)) return false;
    if (weekend && !(day === 0 || day === 6)) return false;

    return datesParser(dates)(date);
  };
};

const rateParser = rulesString => {
  const rules = rulesString.replace(/[\s]/ig, '').split(/[;,]/);

  return date => {
    const passed = rules.map((ruleString, i) => {
      return {passed: ruleParser(ruleString)(date), index: i};
    }).filter(p => p.passed);

    if (passed.length > 1) {
      throw "Пересечение в сезоне ";
    }

    return !!passed.length;
  }
};


export default rateParser;