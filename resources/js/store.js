import Vue from 'vue';
import Vuex from "vuex";

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        user: {
            name: "",
            type: "",
        }
    },
    mutations: {
        setUser(state, user) {
            state.user.id = user.id;
            state.user.name = user.name;
            state.user.permissions = user.permissions;
            state.user.roles = user.roles;
        }
    },
    actions: {
        setUser(context, user) {
            context.commit('setUser', user);
        }
    }
})