"use strict";

import Appearance from '../../../../hotel/store/modules/Appearance';

Appearance.state.bookingVisible = false;

Appearance.mutations.toggleBooking = state => {
  state.bookingVisible = !state.bookingVisible;
  window.overlayCount += state.bookingVisible ? 1 : -1;
};

Appearance.state.datepickerVisible = false;

Appearance.mutations.hideDatepicker = state => {
  state.datepickerVisible = false;
  window.overlayCount --;
};


export default Appearance;