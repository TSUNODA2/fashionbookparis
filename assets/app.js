/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import './styles/navbar.css';
import './styles/footer.css';

// start the Stimulus application
import './bootstrap';


/*  Market Place   */

window.onload = () => {

    // get all elements
    const elts = document.querySelectorAll(".accordionElement");

    for(let elt of elts){
        elt.addEventListener("click", function(){

            // get active element
            const active = document.querySelector(".accordion .active");

            if(active != null){
                // remove active class
                active.classList.remove("active");
                active.children[1].style.height = 0 ;
            }
            // active class on click element
            this.classList.add("active");

            // get height of ""
            let section = this.children[1].querySelector("div");
            // get height of section
            let sectionHeight = section.offsetHeight + 20;

            //height of .accordionContent
            this.children[1].style.height = sectionHeight+"px";

        });
 
   }

}