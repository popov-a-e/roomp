/**
 * Created by Thunder on 17.03.2017.
 */

import App from './search'

export default context => {
  return Promise.resolve(
    new Vue({
      render: h => h(App)
    })
  );
}