"use strict";
import CurrentRoom from '../../../../hotel/store/modules/CurrentRoom';

const body = document.querySelector('body');

const pick = CurrentRoom.mutations.pick;
CurrentRoom.mutations.pick = (state, value) => {
  pick(state,value);
  window.overlayCount++;
};

const unpick = CurrentRoom.mutations.unpick;
CurrentRoom.mutations.unpick = state => {
  unpick(state);
  window.overlayCount --;
};

export default CurrentRoom;