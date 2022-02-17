
const routes = [
  {
    path: "/",
    component: () => import("layouts/MainLayout.vue"),
    children: [{
      path: "",
      component: () => import("pages/Dashboard.vue"),
      meta: { requiresAuth: true },
    }],
  },
  {
    path: "/dashboard",
    component: () => import("layouts/MainLayout.vue"),
    children: [{
      name: 'dashboard',
      path: "",
      component: () => import("pages/Dashboard.vue"),
      meta: { requiresAuth: true },
    }],
  },
  {
    path: "/setup",
    component: () => import("layouts/SetupWizardLayout.vue"),
    children: [
      {
        name: "setup",
        path: ":vaults?",
        component: () => import("pages/SetupWizard.vue"),
        meta: { requiresAuth: false },
      },
    ],
  },
  {
    path: "/manage-notifications",
    component: () => import("layouts/MainLayout.vue"),
    children: [{
      name: "manage-notifications",
      path: "",
      component: () => import("src/pages/ManageNotifications.vue"),
      meta: { requiresAuth: true },
    }],
  },
  {
    path: "/manage-vaults",
    component: () => import("layouts/MainLayout.vue"),
    children: [{
      name: "manage-vaults",
      path: "",
      component: () => import("pages/ManageVaults.vue"),
      meta: { requiresAuth: true },
    }],
  },
  {
    path: "/settings",
    component: () => import("layouts/MainLayout.vue"),
    children: [{
      name: "settings",
      path: "",
      component: () => import("pages/Settings.vue"),
      meta: { requiresAuth: true },
    }],
  },
  {
    path: "/wtf",
    component: () => import("layouts/MainLayout.vue"),
    children: [{
      name: "wtf",
      path: "",
      component: () => import("pages/Wtf.vue"),
      meta: { requiresAuth: false },
    }],
  },
  {
    path: "/statistics",
    component: () => import("layouts/MainLayout.vue"),
    children: [{
      name: "statistics",
      path: "",
      component: () => import("pages/Statistics.vue"),
      meta: { requiresAuth: false },
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
