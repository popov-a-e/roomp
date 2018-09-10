export default {
  namespaced: true,
  state: {
    visible: false,
    menuVisible: false,
    stage: 'login',
    codeSent: false,
    heightFixed: false,
    mobile: false,
    promoOverlayVisible: false
  },
  mutations: {
    fixHeight: state => state.heightFixed = true,
    unfixHeight: state => state.heightFixed = false,
    show: state => {
      ga('send', 'event', 'header', 'enter');
      state.visible = true;
    },
    justHide: state => {
      ga('send', 'event', 'header', 'exit');
      state.visible = false;
    },
    hide: state => {
      state.stage = 'login';
      state.visible = false;
      state.codeSent = false;
    },
    togglePromoOverlay: state => {
      ga('send', 'event', 'header', 'how_it_works');
      state.promoOverlayVisible = !state.promoOverlayVisible;
    },
    setMobile: state => state.mobile = true,
    toRegister: state => state.stage = 'register',
    toLogin: state => state.stage = 'login',
    toForgot: state => state.stage = 'forgot-pass',
    toForgotByPhone: state => state.stage = 'forgot-by-phone',
    toForgotByEmail: state => state.stage = 'forgot-by-email',
    toVerify: state => {
      state.visible = true;
      state.stage = 'verify-phone'
    },
    hideMenu: state => state.menuVisible = false,
    toggleMenu: state => {
      state.menuVisible = !state.menuVisible;

      if (state.mobile) {
        if (state.menuVisible) window.overlayCount++;
        else window.overlayCount--;
      }
    },
    codeSent: state => state.codeSent = true
  }
}