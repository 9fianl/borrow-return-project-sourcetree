export function getAut() {
    const token = localStorage.getItem('token');
    if (!token) return {};

    return {
        headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json',       // เพิ่มเพื่อให้ Laravel ตอบกลับเป็น JSON เสมอ
            'Content-Type': 'application/json'  // เพิ่มเพื่อให้ส่งข้อมูลหา Server ได้ถูกต้อง
        }
    };
}

export function isAuthenticated(){
    return !!localStorage.getItem('token')
}

export function getRole(){
    return localStorage.getItem('role')
}

export function setUserSession(id,token,role){
    localStorage.setItem('id',id)
    localStorage.setItem('token',token)
    localStorage.setItem('role',role)
}

export function logout(){
    localStorage.clear()
    window.location.href='/login'
}