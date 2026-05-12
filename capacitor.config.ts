import { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
  appId: 'com.edumaison.app',
  appName: 'EduMaison',
  webDir: 'public/react',
  server: {
    // En développement WiFi local — commenter pour la prod APK
    url: 'http://192.168.100.106',
    cleartext: true,
  },
  android: {
    allowMixedContent: true,
    backgroundColor: '#E8DCC8',
  },
  plugins: {
    SplashScreen: {
      launchShowDuration: 2000,
      backgroundColor: '#1D6B2A',
      showSpinner: false,
    },
  },
};

export default config;

