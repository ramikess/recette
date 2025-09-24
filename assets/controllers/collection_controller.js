import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['collectionContainer'];

    addCollectionElement(event) {
        // empêche le comportement par défaut du bouton
        event.preventDefault();
        // récupère le prototype HTML défini dans ton Twig
        const prototype = this.collectionContainerTarget.dataset.prototype;
        const index = this.collectionContainerTarget.children.length;

        // Remplace __name__ par l'index courant
        const newForm = prototype.replace(/__name__/g, index);

        this.collectionContainerTarget.insertAdjacentHTML('beforeend', newForm);
    }

    deleteCollectionElement(event) {
        event.preventDefault();
        event.target.closest('.ingredient-item').remove();
    }
}
