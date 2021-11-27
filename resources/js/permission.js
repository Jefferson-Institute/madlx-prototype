import router from './router';
import store from './store';
import NProgress from 'nprogress'; // progress bar
import 'nprogress/nprogress.css'; // progress bar style
import getPageTitle from '@/utils/get-page-title';

NProgress.configure({ showSpinner: false }); // NProgress Configuration

router.beforeEach((to, from, next) => {
  // start progress bar
  NProgress.start();
  // set page title
  document.title = getPageTitle(to.meta.title);

  store.dispatch('permission/generateRoutes');
  // set Token if not Defined
  if (to.query.d_id) {
    next();
  } else if (from.query.d_id) {
    if (from.path !== to.path) {
      next({ ...to, query: { d_id: from.query.d_id }});
    } else {
      next(false);
      NProgress.done();
    }
  } else {
    next({ ...to, query: { d_id: generateUUID() }});
  }
});

router.afterEach(() => {
  // finish progress bar
  NProgress.done();
});

function generateUUID() {
  var d = new Date().getTime();
  var newGuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
    var r = (d + Math.random() * 16) % 16 | 0;
    d = Math.floor(d / 16);
    return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
  });

  return newGuid;
}
