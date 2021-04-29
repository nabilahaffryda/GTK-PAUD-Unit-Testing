import Vue from 'vue';
import * as Sentry from '@sentry/vue';

if (process.env.NODE_ENV !== 'development') {
  Sentry.init({
    Vue: Vue,
    dsn: 'https://e3a97e9478414aa4b9f6e94eeb2e3f1c@sentry.siap.id/36',
    environment: process.env.VUE_APP_ENV,
    release: process.env.VUE_APP_RELEASE,
    ignoreErrors: [
      'ReportingObserver [deprecation]',
      'ResizeObserver loop limit exceeded',
      "Cannot read property 'getReadModeConfig' of undefined",
      "Cannot read property 'getReadModeExtract' of undefined",
      "Cannot read property 'getReadModeRender' of undefined",
    ],
    denyUrls: [/extensions\//i, /^chrome:\/\//i, /^chrome-extensions:\/\//i],
    beforeSend: (event) => {
      // Check if it is an exception -> Show report dialog
      const eventError = (event.exception.values || []).filter(
        (item) => item.mechanism.type === 'onunhandledrejection'
      );
      if (eventError && eventError.length) {
        return null;
      }
      return event;
    },
  });

  Sentry.configureScope(function (scope) {
    scope.setTag('app', 'frontend ' + window.location.host);
  });
}
