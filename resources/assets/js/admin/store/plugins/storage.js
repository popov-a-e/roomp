import Reservation from '../modules/card/Reservation';
import Reservations from '../modules/Reservations';
import Hotel from '../modules/card/Hotel';

const modules = {
  Reservation, Hotel, Reservations
};

const storage = window.localStorage;
const key = 'vuex';

export default function createPersistedState(
  {
    getState = () => {
      const value = storage.getItem(key);
      return value && value !== 'undefined' ? JSON.parse(value) : undefined;
    },
    setState = (state) =>
      storage.setItem(key, JSON.stringify(state))
  } = {}
) {
  return store => {
    const savedState = getState();

    for (let key in savedState) {
      if (!savedState.hasOwnProperty(key)) continue;

      if (key.indexOf('#') >= 0) {
        const moduleName = key.split('#')[0];

        store.registerModule(key, modules[moduleName]);
      }
    }

    if (typeof savedState === 'object') {
      store.replaceState(Object.assign({}, store.state, savedState));
    }

    store.subscribe((mutation, state) => {
      if (mutation.type.match(/.+\/delete$/)) {
        const module = mutation.payload;

        store.unregisterModule(module);
      }

      setState(state);
    });
  };
}