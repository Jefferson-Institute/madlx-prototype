import { constantRoutes } from '@/router';

const state = {
  routes: [],
  addRoutes: [],
};

const mutations = {
  SET_ROUTES: (state) => {
    state.routes = constantRoutes;
  },
};

const actions = {
  generateRoutes({ commit }) {
    commit('SET_ROUTES');
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
