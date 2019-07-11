import Vue from 'vue';
import VueRouter from 'vue-router';

// Containers
import DefaultContainer from '@/containers/DefaultContainer';

import Home from '@/views/Home';
import WebchatSetting from '@/views/WebchatSetting';
import MessageTemplate from '@/views/MessageTemplate';

Vue.use(VueRouter);

const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/admin',
      component: DefaultContainer,
      children: [
        {
          path: '/',
          name: 'home',
          component: Home,
        },
        {
          path: 'webchat-setting',
          name: 'webchat-setting',
          component: WebchatSetting,
        },
        {
          path: 'webchat-setting/:id',
          component: WebchatSetting,
          props: true,
        },
        {
          path: 'outgoing-intents/:outgoingIntent/message-templates',
          name: 'message-templates',
          component: MessageTemplate,
          props: true,
        },
        {
          path: 'outgoing-intents/:outgoingIntent/message-templates/:id',
          name: 'view-message-template',
          component: MessageTemplate,
          props: true,
        },
        {
          path: 'outgoing-intents/:outgoingIntent/message-templates/:id/edit',
          name: 'edit-message-template',
          component: MessageTemplate,
          props: true,
        },
      ],
    },
  ],
});

export default router;
