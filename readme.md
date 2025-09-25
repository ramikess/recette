# 🚀 Installation front Symfony avec Webpack Encore, Stimulus, Turbo et Bootstrap

## 🔹 Étape 1 : Installer le bundle Symfony

```bash
composer require symfony/webpack-encore-bundle
```

---

## 🔹 Étape 2 : Installer Webpack Encore et ses dépendances

```bash
yarn add --dev @symfony/webpack-encore
yarn add --dev webpack webpack-cli
yarn add --dev sass-loader sass css-loader mini-css-extract-plugin
```

✅ Ça installe **Encore + Webpack** et les **loaders** pour compiler ton CSS/SCSS.

---

## 🔹 Étape 3 : Installer Babel et ses presets

```bash
yarn add --dev @babel/core @babel/preset-env babel-loader core-js regenerator-runtime
```

- `@babel/core` → moteur Babel
- `@babel/preset-env` → preset moderne (support ES6+)
- `babel-loader` → plugin Webpack pour Babel
- `core-js` + `regenerator-runtime` → polyfills pour compatibilité navigateurs

---

## 🔹 Étape 4 : Installer Stimulus

```bash
composer require symfony/stimulus-bundle
yarn add @hotwired/stimulus @symfony/stimulus-bridge
```

---

## 🔹 Étape 5 : Modifier `bootstrap.js`

👉 Fichier : `assets/bootstrap.js`

```js
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
```

---

## 🔹 Étape 6 : Installer Turbo et Bootstrap

### Turbo
```bash
composer require symfony/ux-turbo
yarn add @symfony/ux-turbo
yarn add @hotwired/turbo
```

### Bootstrap
```bash
yarn add bootstrap @popperjs/core
mv assets/styles/app.css assets/styles/app.scss
```

👉 Tu pourras importer Bootstrap dans `assets/styles/app.scss` :

```scss
@import "~bootstrap/scss/bootstrap";
```

---

## ✅ Résultat
- Webpack Encore est configuré
- Stimulus est prêt pour tes controllers JS
- Turbo Drive/Streams est disponible
- Bootstrap est intégré pour le style  