import VueRouter from 'vue-router';
import Vue from 'vue';
import ResourceIndex from './pages/ResourceIndex';

Vue.use(VueRouter);

const resourceRoutes = window.structure.resources.reduce((routes, resource) => {
    return routes.concat([
        {
            path: window.structure.base_path + '/' + resource.path,
            component: ResourceIndex,
            name: resource.name + '.index',
            props: {
                resource,
            }
        },
    ]);
}, []);

const routes = [
    ...resourceRoutes,
];


  
  // 3. Create the router instance and pass the `routes` option
  // You can pass in additional options here, but let's
  // keep it simple for now.
  const router = new VueRouter({
    routes, // short for `routes: routes`
    mode: 'history'
  });

  export default router;