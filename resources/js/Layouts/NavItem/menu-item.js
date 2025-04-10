export const menuItem = [
    {
        title:'Dashboard',
        icon: 'solar:layers-bold-duotone',
        BgColor: 'success',
        to: "dashboard",
        permissions: [],
        route:'dashboard'
    },
    {
        title:'Empresas',
        icon: 'solar:buildings-2-bold-duotone',
        BgColor: 'primary',
        to: "enterprises.index",
        permissions: ['enterprise-list'],
        route:'enterprises.*'
    },
    {
        title:'Obras',
        icon: 'emojione-monotone:building-construction',
        BgColor: 'primary',
        to: "constructions.index",
        permissions: ['construction-list'],
        route:'constructions.*'
    },
    {
        title:'Usuários',
        icon: 'solar:user-id-outline',
        BgColor: 'secondary',
        to: "users.index",
        permissions: ['user-list'],
        route:'users.*'
    },
]
