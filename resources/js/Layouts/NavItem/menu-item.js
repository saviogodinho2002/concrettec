export const menuItem = [
    {
        title:'Dashboard',
        icon: 'solar:layers-bold-duotone',
        BgColor: 'success',
        to: "dashboard",
        permissions: ['dashboard-access'],
        route:'dashboard'
    },
    {
        title:'Usuários',
        icon: 'solar:user-id-outline',
        BgColor: 'secondary',
        to: "users.index",
        permissions: ['user-list'],
        route:'users.*'
    },
    {
        title:'Empresas',
        icon: 'solar:building-bold-duotone',
        BgColor: 'primary',
        to: "enterprises.index",
        permissions: ['enterprise-list'],
        route:'enterprises.*'
    },
]
