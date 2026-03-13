import { getRole,isAuthenticated } from "../utilities/api";

export default function authMiddleware(to,from,next){

    if(to.meta.public){
        if(isAuthenticated()){
            return next('/dashboard')
        }

        return next();
    }

    if(!to.meta.public){
        if(!isAuthenticated()){
            alert('กรุณาเข้าสู่ระบบก่อนใช้งาน');
            return next('/login')
        }
    }

    if(to.meta?.roles){
        if(!to.meta.roles.includes(getRole())){
            alert('คุณไม่มีสิทธิ์เข้าถึง หน้านี้ !')
            return next('/no-permission')
        }
    }

    return next();
}