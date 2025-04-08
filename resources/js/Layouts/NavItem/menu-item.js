
export const menuItem = [
    {
        title:'Dashboard',
        icon: 'solar:layers-bold-duotone',
        BgColor: 'success',
        to: "dashboard",
        roles:['Master','Gestor','Coordenador'],
        route:'dashboard'
    },
    {
        title:'Usuários',
        icon: 'solar:user-id-outline',
        BgColor: 'secondary',
        to: "users.index",
        roles:['Master'],
        route:'users.*'
    },
]
