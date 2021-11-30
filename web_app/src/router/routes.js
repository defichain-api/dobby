
const routes = [
  {
    path: "/",
    component: () => import("layouts/MainLayout.vue"),
    children: [{
      path: "",
      component: () => import("pages/Dashboard.vue")
    }],
  },
  {
    path: "/dashboard",
    component: () => import("layouts/MainLayout.vue"),
    children: [{
      name: 'dashboard',
      path: "",
      component: () => import("pages/Dashboard.vue")
    }],
  },
  {
    path: "/setup",
    component: () => import("layouts/SetupWizardLayout.vue"),
    children: [{
      name: "setup",
      path: "",
      component: () => import("pages/SetupWizard.vue")
    }],
  },
  {
    path: "/manage-notifications",
    component: () => import("layouts/MainLayout.vue"),
    children: [{
      name: "manage-notifications",
      path: "",
      component: () => import("pages/ManageNotifications.vue")
    }],
  },
  {
    path: "/manage-vaults",
    component: () => import("layouts/MainLayout.vue"),
    children: [{
      name: "manage-vaults",
      path: "",
      component: () => import("pages/ManageVaults.vue")
    }],
  },
  {
    path: "/settings",
    component: () => import("layouts/MainLayout.vue"),
    children: [{
      name: "settings",
      path: "",
      component: () => import("pages/Settings.vue")
    }],
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: "/:catchAll(.*)*",
    component: () => import("pages/Error404.vue"),
  },
];

export default routes
