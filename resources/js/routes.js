// import App from "./components/App.vue";
import AddPost from "./components/AddPost.vue";

export const routes = [
    // {
    //     name: 'home',
    //     path: '/',
    //     component: AllProduct
    // },
    {
        name: 'create',
        path: '/store',
        component: AddPost
    }
    // ,
    // {
    //     name: 'edit',
    //     path: '/edit/:id',
    //     component: EditProduct
    // }
];