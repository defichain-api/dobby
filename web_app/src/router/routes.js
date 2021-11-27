
const routes = [
  {
    path: "/",
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

  // Always leave this as last one,
  // but you can also remove it
  {
    path: "/:catchAll(.*)*",
    component: () => import("pages/Error404.vue"),
  },
];

export default routes
