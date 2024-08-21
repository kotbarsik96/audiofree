const routes = [
  {
    path: "/reset-password",
    name: "ResetPassword",
    component: () => import("@/views/confirmations/ResetPasswordPage.vue"),
  },

  {
    path: "/",
    name: "Home",
    component: () => import("@/views/public/HomePage.vue"),
  },
  {
    path: "/cart",
    name: "Cart",
    component: () => import("@/views/public/CartPage.vue"),
  },
  {
    path: "/favorites",
    name: "Favorites",
    component: () => import("@/views/public/FavoritesPage.vue"),
  },

  {
    path: "/contacts",
    name: "Contacts",
    component: () => import("@/views/public/static/ContactsPage.vue"),
  },
  {
    path: "/delivery-and-payment",
    name: "DeliveryPayment",
    component: () => import("@/views/public/static/DeliveryPaymentPage.vue"),
  },
  {
    path: "/guarantees-and-refund",
    name: "GuaranteesRefund",
    component: () => import("@/views/public/static/GuaranteesRefundPage.vue"),
  },
  {
    path: "/pickups",
    name: "PickupPlaces",
    component: () => import("@/views/public/static/PickupPlacesPage.vue"),
  },
  {
    path: "/catalog",
    name: "Catalog",
    component: () => import("@/views/public/CatalogPage.vue"),
  },
  {
    path: "/profile",
    name: "Profile",
    component: () => import("@/views/public/profile/ProfilePage.vue"),
  },
]

export default routes
