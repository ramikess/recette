// assets/bootstrap.js
import { startStimulusApp } from '@symfony/stimulus-bridge';
import controllers from './controllers.json';

// Initialise Stimulus avec les contrôleurs déclarés
const app = startStimulusApp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.[jt]sx?$/
));

// Tu peux enregistrer des contrôleurs custom ici si besoin
// app.register('some_controller_name', SomeImportedController);

export default app;

