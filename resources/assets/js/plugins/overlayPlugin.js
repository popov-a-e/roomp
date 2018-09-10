export default store => {
  window.overlayCount = 0;

  store.subscribe((mutation, rootState) => {
    if (window.overlayCount > 0) {
      document.body.className = 'fixed';
    } else {
      document.body.className = '';
    }
  });
};