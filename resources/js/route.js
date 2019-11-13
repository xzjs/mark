import Book from "./components/Book";
import Document from "./components/Document";
import User from "./components/User";
import Password from "./components/Password";
import VueRouter from "vue-router";
import Math from "./components/Math";
import Computer from "./components/Computer";

export default new VueRouter({
    routes: [
        {path: '/', component: Book},
        {path: '/book', component: Book},
        {path: '/document', component: Document},
        {path: '/user', component: User},
        {path: '/password', component: Password},
        {path: '/math', component: Math},
        {path: '/computer', component: Computer}
    ]
})