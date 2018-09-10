<template>
  <div class='countryinput' v-on:click.stop.prevent='hide'>
    <div class='country-selected' v-on:click='toggle'>
      <div class='flag' v-bind:class='code'></div>
    </div>
    <div class='menu' v-if='visible' v-on:wheel='wheel'>
      <a v-for='country in countries' @click.stop.prevent='select(country)' @touchend.stop.prevent='select(country)'>
        <div class='flag-container'>
          <div class='flag' v-bind:class='country.code'></div>
        </div>
        <div class='country-name'>
          {{ country.en }}
        </div>
        <div class='country-code'>
          +{{ country.phone }}
        </div>
      </a>
    </div>
    <input name='phone' v-bind:disabled='disabled' @keydown='keydown' :placeholder="__('header.phone_number')" @input='input'/>
  </div>
</template>

<script>
  import {mapState, mapActions, mapMutations, mapGetters} from 'vuex';
  import countries from './countries.js';
  import Bus from './../../Bus';


  export default {
    data () {
      return {
        countries,
        code: 'ru',
        visible: false,
        phone: '',
        value: '',
        phoneNumber: '+7 ',
        inputable: false,
        keyDownFired: false
      }
    },
    props: ['phoneInit', 'disabled'],
    methods: {
      wheel (e) {
        e.stopPropagation();
      },
      toggle() {
        if (this.disabled) return;
        this.visible = !this.visible;
      },
      hide () {
        Bus.$emit('click', this._uid);
      },
      select(country) {
        const caret = this.input.selectionStart;

        this.code = country.code;
        this.toggle();

        this.updateInput();

        if (this.phone.length > 0) this.input.setSelectionRange(caret, caret);
      },
      getPhoneNumber () {
        if (this.code === null) {
          return this.value;
        }

        return '+' + this.countries.where('code', this.code).phone + ' ' + this.phoneFormatted;
      },
      input (event) {
        if (!this.keyDownFired) {
          event.preventDefault();
          return;
        }
        if (!this.inputable) {
          event.preventDefault();
          return false;
        } else {
          let value = event.target.value.replace(/\D/ig,'');
          
          let country = this.countries.filter(country => {
            let regex = new RegExp('^' + country.phone + '[\\d]*$');
  
            return value.match(regex);
          });

          if (country.length > 1) {
            country = country.reduce(function (p, c) {
              if (p.phone.length < c.phone.length || (p.phone.length === c.phone.length && c.default === true)) return c;
              return p;
            });

            this.code = country.code;
          } else if (country.length === 1) {
            country = country[0];
            this.code = country.code;
          }

          if (country.length === 0) {
            event.preventDefault();
            this.updateInput();
            return false;
          }
          
          const regex = new RegExp(`^${country.phone}(\\d+)$`);
          
          value = value.match(regex)[1];

          this.phone = value;

          this.phoneNumber = this.getPhoneNumber();

          this.updateInput();
          
          this.inputable = false;
        }
      },
      keydown (event, native = true) {
        this.keyDownFired = native;
        let key = event.key;
        let value = event.target.value;
        let selStart = Math.min(event.target.selectionStart, event.target.selectionEnd);
        let selEnd = Math.max(event.target.selectionStart, event.target.selectionEnd);
        let caret = selStart;
        
        if(!key) {
          this.inputable = true;
          
          return true;
        }
        
        if (key.length === 1 || key === 'Enter') event.preventDefault();

        if (key === 'Backspace' || key === 'Delete') {
          event.preventDefault();

          if (selEnd - selStart === 0) {
            if (key === 'Delete') {
              selEnd++;
            } else {
              selStart--;
              caret--;

              if (value[selStart] === ' ' || value[selStart] === '-' ) {
                selStart--;
                caret--;
              }
            }
          }

          value = [...value];
          value.splice(selStart, selEnd - selStart);
          value = value.join('');

        } else {
          if (!key.match(/^\d$/)) return;

          value = [...value];

          value.splice(selStart, selEnd - selStart, key);
          value = value.join('');

          caret++;
        }

        value = value.replace(/[^\d]/ig, '');

        let country = this.countries.filter(country => {
          let regex = new RegExp('^' + country.phone + '[\\d]*$');

          return value.match(regex);
        });

        if (country.length > 1) {
          country = country.reduce(function (p, c) {
            if (p.phone.length < c.phone.length || (p.phone.length === c.phone.length && c.default === true)) return c;
            return p;
          });

          this.code = country.code;
        } else if (country.length === 1) {
          country = country[0];
          this.code = country.code;
        }

        if (!country || country.length === 0) {
          this.value = '+' + value;
          this.code = null;
        }

        let phone = this.code ? value.slice((country.phone + '').length, value.length) : '';

        if (phone.length > 10) return;
        this.phone = phone;
        
        this.phoneNumber = this.getPhoneNumber();

        this.updateInput();
        if (phone.length === 4 || phone.length === 7 || phone.length === 9) caret ++;
        
        if (phone.length > 0) event.target.setSelectionRange(caret, caret);
      },
      updateInput() {
        this.input.value = this.getPhoneNumber();
        this.$emit('input', this.getPhoneNumber());
      }
    },
    mounted () {
      Bus.$on('click', (_uid) => {
        if (this._uid !== _uid) this.visible = false;
      });

      this.input = this.$el.querySelector('input');
      
      if (this.phoneInit) {
        let init = this.phoneInit;

        let codeLen = init.length - 10;

        this.phone = init.substr(codeLen, 10);
        
        let country = this.countries.filter(country => {
          let regex = new RegExp('^' + country.phone + '[\\d]*$');

          return init.match(regex);
        });
        
        if (country.length > 1) {
          country = country.reduce(function (p, c) {
            if (p.phone.length < c.phone.length || (p.phone.length === c.phone.length && c.default === true)) return c;
            return p;
          });

          this.code = country.code;
        } else if (country.length === 1) {
          country = country[0];
          this.code = country.code;
        }

        if (this.code) {
          const regex = new RegExp(country.phone + "(\\d+)");
          
          this.phone = this.phoneInit.match(regex)[1];
        }
      }
      
      this.updateInput();
    },
    computed: {
      ...mapState('Header', ['mobile']),
      phoneFormatted () {
        const len = this.phone.length;
        
        switch (true) {
          case (len <= 3): return this.phone;
          case (len <= 6): return this.phone.substr(0,3) + '-' + this.phone.substr(3,3);
          case (len <= 8): return this.phone.substr(0,3) + '-' + this.phone.substr(3,3) + '-' + this.phone.substr(6,2);
          case (len <= 10): return this.phone.substr(0,3) + '-' + this.phone.substr(3,3) + '-' + this.phone.substr(6,2) + '-' + this.phone.substr(8,2);
        }
      }
    }
  }
</script>

<style scoped lang='scss'>
  $flagHeight: 15px !default;
  $flagWidth: 20px !default;
  
  .countryinput {
    width: 100%;
    height: 50px;
    line-height: 50px;
    position: relative;
    float: left;
    margin-top: 10px;
    
    input {
      width: calc(100% - 60px) !important;
      margin: 0 !important;
    }
    
    .country-selected, .flag-container {
      cursor: pointer;
      display: flex;
      float: left;
      width: 60px;
      height: 50px;
      line-height: 50px;
      align-items: center;
      justify-content: center;
      position: relative;
      
      &.country-selected:hover {
        background: #f0f0f0;
      }
    }
    
    .flag-container, .country-name, .country-code {
      float: left;
      position: relative;
      text-align: left;
      height: 30px;
      line-height: 30px;
      max-width: calc(100% - 100px);
      white-space: nowrap;
      text-overflow: ellipsis;
    }
    
    .country-code {
      color: #adadad;
      margin-left: 10px;
    }
    
    .menu {
      width: 100%;
      height: 240px;
      position: absolute;
      top: 100%;
      z-index: 900;
      background: white;
      overflow-y: scroll;
      box-shadow: 0 2px 2px -1px;
      
      a {
        width: 100%;
        float: left;
        position: relative;
        height: 30px;
        line-height: 30px;
        cursor: pointer;
        overflow: hidden;
        text-overflow: ellipsis;
        
        .flag {
          float: left;
          margin: 7.5px 0;
        }
      }
    }
  }
  
  .flag {
    width: $flagWidth;
    height: $flagHeight;
    box-shadow: 0px 0px 1px 0px #888;
    background-image: url('/img/flags.png');
    background-repeat: no-repeat;
    background-color: #DBDBDB;
    background-position: $flagWidth 0;
    
    $item-width-maps: (ac: 20px, ad: 20px, ae: 20px, af: 20px, ag: 20px, ai: 20px, al: 20px, am: 20px, ao: 20px, aq: 20px, ar: 20px, as: 20px, at: 20px, au: 20px, aw: 20px, ax: 20px, az: 20px, ba: 20px, bb: 20px, bd: 20px, be: 18px, bf: 20px, bg: 20px, bh: 20px, bi: 20px, bj: 20px, bl: 20px, bm: 20px, bn: 20px, bo: 20px, bq: 20px, br: 20px, bs: 20px, bt: 20px, bv: 20px, bw: 20px, by: 20px, bz: 20px, ca: 20px, cc: 20px, cd: 20px, cf: 20px, cg: 20px, ch: 15px, ci: 20px, ck: 20px, cl: 20px, cm: 20px, cn: 20px, co: 20px, cp: 20px, cr: 20px, cu: 20px, cv: 20px, cw: 20px, cx: 20px, cy: 20px, cz: 20px, de: 20px, dg: 20px, dj: 20px, dk: 20px, dm: 20px, do: 20px, dz: 20px, ea: 20px, ec: 20px, ee: 20px, eg: 20px, eh: 20px, er: 20px, es: 20px, et: 20px, eu: 20px, fi: 20px, fj: 20px, fk: 20px, fm: 20px, fo: 20px, fr: 20px, ga: 20px, gb: 20px, gd: 20px, ge: 20px, gf: 20px, gg: 20px, gh: 20px, gi: 20px, gl: 20px, gm: 20px, gn: 20px, gp: 20px, gq: 20px, gr: 20px, gs: 20px, gt: 20px, gu: 20px, gw: 20px, gy: 20px, hk: 20px, hm: 20px, hn: 20px, hr: 20px, ht: 20px, hu: 20px, ic: 20px, id: 20px, ie: 20px, il: 20px, im: 20px, in: 20px, io: 20px, iq: 20px, ir: 20px, is: 20px, it: 20px, je: 20px, jm: 20px, jo: 20px, jp: 20px, ke: 20px, kg: 20px, kh: 20px, ki: 20px, km: 20px, kn: 20px, kp: 20px, kr: 20px, kw: 20px, ky: 20px, kz: 20px, la: 20px, lb: 20px, lc: 20px, li: 20px, lk: 20px, lr: 20px, ls: 20px, lt: 20px, lu: 20px, lv: 20px, ly: 20px, ma: 20px, mc: 19px, md: 20px, me: 20px, mf: 20px, mg: 20px, mh: 20px, mk: 20px, ml: 20px, mm: 20px, mn: 20px, mo: 20px, mp: 20px, mq: 20px, mr: 20px, ms: 20px, mt: 20px, mu: 20px, mv: 20px, mw: 20px, mx: 20px, my: 20px, mz: 20px, na: 20px, nc: 20px, ne: 18px, nf: 20px, ng: 20px, ni: 20px, nl: 20px, no: 20px, np: 13px, nr: 20px, nu: 20px, nz: 20px, om: 20px, pa: 20px, pe: 20px, pf: 20px, pg: 20px, ph: 20px, pk: 20px, pl: 20px, pm: 20px, pn: 20px, pr: 20px, ps: 20px, pt: 20px, pw: 20px, py: 20px, qa: 20px, re: 20px, ro: 20px, rs: 20px, ru: 20px, rw: 20px, sa: 20px, sb: 20px, sc: 20px, sd: 20px, se: 20px, sg: 20px, sh: 20px, si: 20px, sj: 20px, sk: 20px, sl: 20px, sm: 20px, sn: 20px, so: 20px, sr: 20px, ss: 20px, st: 20px, sv: 20px, sx: 20px, sy: 20px, sz: 20px, ta: 20px, tc: 20px, td: 20px, tf: 20px, tg: 20px, th: 20px, tj: 20px, tk: 20px, tl: 20px, tm: 20px, tn: 20px, to: 20px, tr: 20px, tt: 20px, tv: 20px, tw: 20px, tz: 20px, ua: 20px, ug: 20px, um: 20px, us: 20px, uy: 20px, uz: 20px, va: 15px, vc: 20px, ve: 20px, vg: 20px, vi: 20px, vn: 20px, vu: 20px, wf: 20px, ws: 20px, xk: 20px, ye: 20px, yt: 20px, za: 20px, zm: 20px, zw: 20px,);
    
    @each $key, $width in $item-width-maps {
      &.#{$key} {
        width: $width;
      }
    }
    
    @media only screen and (-webkit-min-device-pixel-ratio: 2),
    only screen and (min--moz-device-pixel-ratio: 2),
    only screen and (-o-min-device-pixel-ratio: 2/1),
    only screen and (min-device-pixel-ratio: 2),
    only screen and (min-resolution: 192dpi),
    only screen and (min-resolution: 2dppx) {
      background-size: 5630px 15px;
      background-image: url("/img/flags@2x.png");
    }
    
    &.ac {
      height: 10px;
      background-position: 0px 0px;
    }
    &.ad {
      height: 14px;
      background-position: -22px 0px;
    }
    &.ae {
      height: 10px;
      background-position: -44px 0px;
    }
    &.af {
      height: 14px;
      background-position: -66px 0px;
    }
    &.ag {
      height: 14px;
      background-position: -88px 0px;
    }
    &.ai {
      height: 10px;
      background-position: -110px 0px;
    }
    &.al {
      height: 15px;
      background-position: -132px 0px;
    }
    &.am {
      height: 10px;
      background-position: -154px 0px;
    }
    &.ao {
      height: 14px;
      background-position: -176px 0px;
    }
    &.aq {
      height: 14px;
      background-position: -198px 0px;
    }
    &.ar {
      height: 13px;
      background-position: -220px 0px;
    }
    &.as {
      height: 10px;
      background-position: -242px 0px;
    }
    &.at {
      height: 14px;
      background-position: -264px 0px;
    }
    &.au {
      height: 10px;
      background-position: -286px 0px;
    }
    &.aw {
      height: 14px;
      background-position: -308px 0px;
    }
    &.ax {
      height: 13px;
      background-position: -330px 0px;
    }
    &.az {
      height: 10px;
      background-position: -352px 0px;
    }
    &.ba {
      height: 10px;
      background-position: -374px 0px;
    }
    &.bb {
      height: 14px;
      background-position: -396px 0px;
    }
    &.bd {
      height: 12px;
      background-position: -418px 0px;
    }
    &.be {
      height: 15px;
      background-position: -440px 0px;
    }
    &.bf {
      height: 14px;
      background-position: -460px 0px;
    }
    &.bg {
      height: 12px;
      background-position: -482px 0px;
    }
    &.bh {
      height: 12px;
      background-position: -504px 0px;
    }
    &.bi {
      height: 12px;
      background-position: -526px 0px;
    }
    &.bj {
      height: 14px;
      background-position: -548px 0px;
    }
    &.bl {
      height: 14px;
      background-position: -570px 0px;
    }
    &.bm {
      height: 10px;
      background-position: -592px 0px;
    }
    &.bn {
      height: 10px;
      background-position: -614px 0px;
    }
    &.bo {
      height: 14px;
      background-position: -636px 0px;
    }
    &.bq {
      height: 14px;
      background-position: -658px 0px;
    }
    &.br {
      height: 14px;
      background-position: -680px 0px;
    }
    &.bs {
      height: 10px;
      background-position: -702px 0px;
    }
    &.bt {
      height: 14px;
      background-position: -724px 0px;
    }
    &.bv {
      height: 15px;
      background-position: -746px 0px;
    }
    &.bw {
      height: 14px;
      background-position: -768px 0px;
    }
    &.by {
      height: 10px;
      background-position: -790px 0px;
    }
    &.bz {
      height: 14px;
      background-position: -812px 0px;
    }
    &.ca {
      height: 10px;
      background-position: -834px 0px;
    }
    &.cc {
      height: 10px;
      background-position: -856px 0px;
    }
    &.cd {
      height: 15px;
      background-position: -878px 0px;
    }
    &.cf {
      height: 14px;
      background-position: -900px 0px;
    }
    &.cg {
      height: 14px;
      background-position: -922px 0px;
    }
    &.ch {
      height: 15px;
      background-position: -944px 0px;
    }
    &.ci {
      height: 14px;
      background-position: -961px 0px;
    }
    &.ck {
      height: 10px;
      background-position: -983px 0px;
    }
    &.cl {
      height: 14px;
      background-position: -1005px 0px;
    }
    &.cm {
      height: 14px;
      background-position: -1027px 0px;
    }
    &.cn {
      height: 14px;
      background-position: -1049px 0px;
    }
    &.co {
      height: 14px;
      background-position: -1071px 0px;
    }
    &.cp {
      height: 14px;
      background-position: -1093px 0px;
    }
    &.cr {
      height: 12px;
      background-position: -1115px 0px;
    }
    &.cu {
      height: 10px;
      background-position: -1137px 0px;
    }
    &.cv {
      height: 12px;
      background-position: -1159px 0px;
    }
    &.cw {
      height: 14px;
      background-position: -1181px 0px;
    }
    &.cx {
      height: 10px;
      background-position: -1203px 0px;
    }
    &.cy {
      height: 13px;
      background-position: -1225px 0px;
    }
    &.cz {
      height: 14px;
      background-position: -1247px 0px;
    }
    &.de {
      height: 12px;
      background-position: -1269px 0px;
    }
    &.dg {
      height: 10px;
      background-position: -1291px 0px;
    }
    &.dj {
      height: 14px;
      background-position: -1313px 0px;
    }
    &.dk {
      height: 15px;
      background-position: -1335px 0px;
    }
    &.dm {
      height: 10px;
      background-position: -1357px 0px;
    }
    &.do {
      height: 13px;
      background-position: -1379px 0px;
    }
    &.dz {
      height: 14px;
      background-position: -1401px 0px;
    }
    &.ea {
      height: 14px;
      background-position: -1423px 0px;
    }
    &.ec {
      height: 14px;
      background-position: -1445px 0px;
    }
    &.ee {
      height: 13px;
      background-position: -1467px 0px;
    }
    &.eg {
      height: 14px;
      background-position: -1489px 0px;
    }
    &.eh {
      height: 10px;
      background-position: -1511px 0px;
    }
    &.er {
      height: 10px;
      background-position: -1533px 0px;
    }
    &.es {
      height: 14px;
      background-position: -1555px 0px;
    }
    &.et {
      height: 10px;
      background-position: -1577px 0px;
    }
    &.eu {
      height: 14px;
      background-position: -1599px 0px;
    }
    &.fi {
      height: 12px;
      background-position: -1621px 0px;
    }
    &.fj {
      height: 10px;
      background-position: -1643px 0px;
    }
    &.fk {
      height: 10px;
      background-position: -1665px 0px;
    }
    &.fm {
      height: 11px;
      background-position: -1687px 0px;
    }
    &.fo {
      height: 15px;
      background-position: -1709px 0px;
    }
    &.fr {
      height: 14px;
      background-position: -1731px 0px;
    }
    &.ga {
      height: 15px;
      background-position: -1753px 0px;
    }
    &.gb {
      height: 10px;
      background-position: -1775px 0px;
    }
    &.gd {
      height: 12px;
      background-position: -1797px 0px;
    }
    &.ge {
      height: 14px;
      background-position: -1819px 0px;
    }
    &.gf {
      height: 14px;
      background-position: -1841px 0px;
    }
    &.gg {
      height: 14px;
      background-position: -1863px 0px;
    }
    &.gh {
      height: 14px;
      background-position: -1885px 0px;
    }
    &.gi {
      height: 10px;
      background-position: -1907px 0px;
    }
    &.gl {
      height: 14px;
      background-position: -1929px 0px;
    }
    &.gm {
      height: 14px;
      background-position: -1951px 0px;
    }
    &.gn {
      height: 14px;
      background-position: -1973px 0px;
    }
    &.gp {
      height: 14px;
      background-position: -1995px 0px;
    }
    &.gq {
      height: 14px;
      background-position: -2017px 0px;
    }
    &.gr {
      height: 14px;
      background-position: -2039px 0px;
    }
    &.gs {
      height: 10px;
      background-position: -2061px 0px;
    }
    &.gt {
      height: 13px;
      background-position: -2083px 0px;
    }
    &.gu {
      height: 11px;
      background-position: -2105px 0px;
    }
    &.gw {
      height: 10px;
      background-position: -2127px 0px;
    }
    &.gy {
      height: 12px;
      background-position: -2149px 0px;
    }
    &.hk {
      height: 14px;
      background-position: -2171px 0px;
    }
    &.hm {
      height: 10px;
      background-position: -2193px 0px;
    }
    &.hn {
      height: 10px;
      background-position: -2215px 0px;
    }
    &.hr {
      height: 10px;
      background-position: -2237px 0px;
    }
    &.ht {
      height: 12px;
      background-position: -2259px 0px;
    }
    &.hu {
      height: 10px;
      background-position: -2281px 0px;
    }
    &.ic {
      height: 14px;
      background-position: -2303px 0px;
    }
    &.id {
      height: 14px;
      background-position: -2325px 0px;
    }
    &.ie {
      height: 10px;
      background-position: -2347px 0px;
    }
    &.il {
      height: 15px;
      background-position: -2369px 0px;
    }
    &.im {
      height: 10px;
      background-position: -2391px 0px;
    }
    &.in {
      height: 14px;
      background-position: -2413px 0px;
    }
    &.io {
      height: 10px;
      background-position: -2435px 0px;
    }
    &.iq {
      height: 14px;
      background-position: -2457px 0px;
    }
    &.ir {
      height: 12px;
      background-position: -2479px 0px;
    }
    &.is {
      height: 15px;
      background-position: -2501px 0px;
    }
    &.it {
      height: 14px;
      background-position: -2523px 0px;
    }
    &.je {
      height: 12px;
      background-position: -2545px 0px;
    }
    &.jm {
      height: 10px;
      background-position: -2567px 0px;
    }
    &.jo {
      height: 10px;
      background-position: -2589px 0px;
    }
    &.jp {
      height: 14px;
      background-position: -2611px 0px;
    }
    &.ke {
      height: 14px;
      background-position: -2633px 0px;
    }
    &.kg {
      height: 12px;
      background-position: -2655px 0px;
    }
    &.kh {
      height: 13px;
      background-position: -2677px 0px;
    }
    &.ki {
      height: 10px;
      background-position: -2699px 0px;
    }
    &.km {
      height: 12px;
      background-position: -2721px 0px;
    }
    &.kn {
      height: 14px;
      background-position: -2743px 0px;
    }
    &.kp {
      height: 10px;
      background-position: -2765px 0px;
    }
    &.kr {
      height: 14px;
      background-position: -2787px 0px;
    }
    &.kw {
      height: 10px;
      background-position: -2809px 0px;
    }
    &.ky {
      height: 10px;
      background-position: -2831px 0px;
    }
    &.kz {
      height: 10px;
      background-position: -2853px 0px;
    }
    &.la {
      height: 14px;
      background-position: -2875px 0px;
    }
    &.lb {
      height: 14px;
      background-position: -2897px 0px;
    }
    &.lc {
      height: 10px;
      background-position: -2919px 0px;
    }
    &.li {
      height: 12px;
      background-position: -2941px 0px;
    }
    &.lk {
      height: 10px;
      background-position: -2963px 0px;
    }
    &.lr {
      height: 11px;
      background-position: -2985px 0px;
    }
    &.ls {
      height: 14px;
      background-position: -3007px 0px;
    }
    &.lt {
      height: 12px;
      background-position: -3029px 0px;
    }
    &.lu {
      height: 12px;
      background-position: -3051px 0px;
    }
    &.lv {
      height: 10px;
      background-position: -3073px 0px;
    }
    &.ly {
      height: 10px;
      background-position: -3095px 0px;
    }
    &.ma {
      height: 14px;
      background-position: -3117px 0px;
    }
    &.mc {
      height: 15px;
      background-position: -3139px 0px;
    }
    &.md {
      height: 10px;
      background-position: -3160px 0px;
    }
    &.me {
      height: 10px;
      background-position: -3182px 0px;
    }
    &.mf {
      height: 14px;
      background-position: -3204px 0px;
    }
    &.mg {
      height: 14px;
      background-position: -3226px 0px;
    }
    &.mh {
      height: 11px;
      background-position: -3248px 0px;
    }
    &.mk {
      height: 10px;
      background-position: -3270px 0px;
    }
    &.ml {
      height: 14px;
      background-position: -3292px 0px;
    }
    &.mm {
      height: 14px;
      background-position: -3314px 0px;
    }
    &.mn {
      height: 10px;
      background-position: -3336px 0px;
    }
    &.mo {
      height: 14px;
      background-position: -3358px 0px;
    }
    &.mp {
      height: 10px;
      background-position: -3380px 0px;
    }
    &.mq {
      height: 14px;
      background-position: -3402px 0px;
    }
    &.mr {
      height: 14px;
      background-position: -3424px 0px;
    }
    &.ms {
      height: 10px;
      background-position: -3446px 0px;
    }
    &.mt {
      height: 14px;
      background-position: -3468px 0px;
    }
    &.mu {
      height: 14px;
      background-position: -3490px 0px;
    }
    &.mv {
      height: 14px;
      background-position: -3512px 0px;
    }
    &.mw {
      height: 14px;
      background-position: -3534px 0px;
    }
    &.mx {
      height: 12px;
      background-position: -3556px 0px;
    }
    &.my {
      height: 10px;
      background-position: -3578px 0px;
    }
    &.mz {
      height: 14px;
      background-position: -3600px 0px;
    }
    &.na {
      height: 14px;
      background-position: -3622px 0px;
    }
    &.nc {
      height: 10px;
      background-position: -3644px 0px;
    }
    &.ne {
      height: 15px;
      background-position: -3666px 0px;
    }
    &.nf {
      height: 10px;
      background-position: -3686px 0px;
    }
    &.ng {
      height: 10px;
      background-position: -3708px 0px;
    }
    &.ni {
      height: 12px;
      background-position: -3730px 0px;
    }
    &.nl {
      height: 14px;
      background-position: -3752px 0px;
    }
    &.no {
      height: 15px;
      background-position: -3774px 0px;
    }
    &.np {
      height: 15px;
      background-position: -3796px 0px;
    }
    &.nr {
      height: 10px;
      background-position: -3811px 0px;
    }
    &.nu {
      height: 10px;
      background-position: -3833px 0px;
    }
    &.nz {
      height: 10px;
      background-position: -3855px 0px;
    }
    &.om {
      height: 10px;
      background-position: -3877px 0px;
    }
    &.pa {
      height: 14px;
      background-position: -3899px 0px;
    }
    &.pe {
      height: 14px;
      background-position: -3921px 0px;
    }
    &.pf {
      height: 14px;
      background-position: -3943px 0px;
    }
    &.pg {
      height: 15px;
      background-position: -3965px 0px;
    }
    &.ph {
      height: 10px;
      background-position: -3987px 0px;
    }
    &.pk {
      height: 14px;
      background-position: -4009px 0px;
    }
    &.pl {
      height: 13px;
      background-position: -4031px 0px;
    }
    &.pm {
      height: 14px;
      background-position: -4053px 0px;
    }
    &.pn {
      height: 10px;
      background-position: -4075px 0px;
    }
    &.pr {
      height: 14px;
      background-position: -4097px 0px;
    }
    &.ps {
      height: 10px;
      background-position: -4119px 0px;
    }
    &.pt {
      height: 14px;
      background-position: -4141px 0px;
    }
    &.pw {
      height: 13px;
      background-position: -4163px 0px;
    }
    &.py {
      height: 11px;
      background-position: -4185px 0px;
    }
    &.qa {
      height: 8px;
      background-position: -4207px 0px;
    }
    &.re {
      height: 14px;
      background-position: -4229px 0px;
    }
    &.ro {
      height: 14px;
      background-position: -4251px 0px;
    }
    &.rs {
      height: 14px;
      background-position: -4273px 0px;
    }
    &.ru {
      height: 14px;
      background-position: -4295px 0px;
    }
    &.rw {
      height: 14px;
      background-position: -4317px 0px;
    }
    &.sa {
      height: 14px;
      background-position: -4339px 0px;
    }
    &.sb {
      height: 10px;
      background-position: -4361px 0px;
    }
    &.sc {
      height: 10px;
      background-position: -4383px 0px;
    }
    &.sd {
      height: 10px;
      background-position: -4405px 0px;
    }
    &.se {
      height: 13px;
      background-position: -4427px 0px;
    }
    &.sg {
      height: 14px;
      background-position: -4449px 0px;
    }
    &.sh {
      height: 10px;
      background-position: -4471px 0px;
    }
    &.si {
      height: 10px;
      background-position: -4493px 0px;
    }
    &.sj {
      height: 15px;
      background-position: -4515px 0px;
    }
    &.sk {
      height: 14px;
      background-position: -4537px 0px;
    }
    &.sl {
      height: 14px;
      background-position: -4559px 0px;
    }
    &.sm {
      height: 15px;
      background-position: -4581px 0px;
    }
    &.sn {
      height: 14px;
      background-position: -4603px 0px;
    }
    &.so {
      height: 14px;
      background-position: -4625px 0px;
    }
    &.sr {
      height: 14px;
      background-position: -4647px 0px;
    }
    &.ss {
      height: 10px;
      background-position: -4669px 0px;
    }
    &.st {
      height: 10px;
      background-position: -4691px 0px;
    }
    &.sv {
      height: 12px;
      background-position: -4713px 0px;
    }
    &.sx {
      height: 14px;
      background-position: -4735px 0px;
    }
    &.sy {
      height: 14px;
      background-position: -4757px 0px;
    }
    &.sz {
      height: 14px;
      background-position: -4779px 0px;
    }
    &.ta {
      height: 10px;
      background-position: -4801px 0px;
    }
    &.tc {
      height: 10px;
      background-position: -4823px 0px;
    }
    &.td {
      height: 14px;
      background-position: -4845px 0px;
    }
    &.tf {
      height: 14px;
      background-position: -4867px 0px;
    }
    &.tg {
      height: 13px;
      background-position: -4889px 0px;
    }
    &.th {
      height: 14px;
      background-position: -4911px 0px;
    }
    &.tj {
      height: 10px;
      background-position: -4933px 0px;
    }
    &.tk {
      height: 10px;
      background-position: -4955px 0px;
    }
    &.tl {
      height: 10px;
      background-position: -4977px 0px;
    }
    &.tm {
      height: 14px;
      background-position: -4999px 0px;
    }
    &.tn {
      height: 14px;
      background-position: -5021px 0px;
    }
    &.to {
      height: 10px;
      background-position: -5043px 0px;
    }
    &.tr {
      height: 14px;
      background-position: -5065px 0px;
    }
    &.tt {
      height: 12px;
      background-position: -5087px 0px;
    }
    &.tv {
      height: 10px;
      background-position: -5109px 0px;
    }
    &.tw {
      height: 14px;
      background-position: -5131px 0px;
    }
    &.tz {
      height: 14px;
      background-position: -5153px 0px;
    }
    &.ua {
      height: 14px;
      background-position: -5175px 0px;
    }
    &.ug {
      height: 14px;
      background-position: -5197px 0px;
    }
    &.um {
      height: 11px;
      background-position: -5219px 0px;
    }
    &.us {
      height: 11px;
      background-position: -5241px 0px;
    }
    &.uy {
      height: 14px;
      background-position: -5263px 0px;
    }
    &.uz {
      height: 10px;
      background-position: -5285px 0px;
    }
    &.va {
      height: 15px;
      background-position: -5307px 0px;
    }
    &.vc {
      height: 14px;
      background-position: -5324px 0px;
    }
    &.ve {
      height: 14px;
      background-position: -5346px 0px;
    }
    &.vg {
      height: 10px;
      background-position: -5368px 0px;
    }
    &.vi {
      height: 14px;
      background-position: -5390px 0px;
    }
    &.vn {
      height: 14px;
      background-position: -5412px 0px;
    }
    &.vu {
      height: 12px;
      background-position: -5434px 0px;
    }
    &.wf {
      height: 14px;
      background-position: -5456px 0px;
    }
    &.ws {
      height: 10px;
      background-position: -5478px 0px;
    }
    &.xk {
      height: 15px;
      background-position: -5500px 0px;
    }
    &.ye {
      height: 14px;
      background-position: -5522px 0px;
    }
    &.yt {
      height: 14px;
      background-position: -5544px 0px;
    }
    &.za {
      height: 14px;
      background-position: -5566px 0px;
    }
    &.zm {
      height: 14px;
      background-position: -5588px 0px;
    }
    &.zw {
      height: 10px;
      background-position: -5610px 0px;
    }
  }
</style>