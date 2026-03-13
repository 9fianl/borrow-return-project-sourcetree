import { createRouter, createWebHistory } from 'vue-router' 
import authMiddleware from '../middleware/auth'
import upload from '../components/uploadnaja.vue' 
import login from '../components/login.vue'
import register from '../components/register.vue'
import dashboard from '../components/admin/dashboard.vue'
import book from '../components/user/book.vue'
import book_manage from '../components/admin/book_manage.vue'
import category_manage from '../components/admin/category_manage.vue'
import lend_manage from '../components/admin/lend_manage.vue'
import user_manage from '../components/admin/user_manage.vue'


const routes = [
    {path:'/',redirect:'/login'},

    {path:'/login',component:login,meta:{public:true}},
    {path:'/register',component:register,meta:{public:true}},
    {path:'/dashboard',component:dashboard,meta:{roles:['admin']}},
    {path:'/book',component:book,meta:{roles:['user']}},

    {path:'/book_manage',component:book_manage,meta:{roles:['admin']}},
    {path:'/category_manage',component:category_manage,meta:{roles:['admin']}},

    {path:'/lend_manage',component:lend_manage,meta:{roles:['admin','user']}},
    {path:'/user_manage',component:user_manage,meta:{roles:['admin']}},


]

const router = createRouter({
    history: createWebHistory(), 
    routes
})

router.beforeEach(authMiddleware)

export default router