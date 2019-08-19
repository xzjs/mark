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
            state.user.name = user.name;
            state.user.type = user.type;
        }
    },
    actions: {
        setUser(context, user) {
            context.commit('setUser', user);
        }
    }
})