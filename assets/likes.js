
function onClickBtnLike(event) {
    event.preventDefault();

    const url = this.href;
    const spanCount = this.querySelector('span.post-like-count');
    const icone = this.querySelector('i');
    const likeLabel = this.querySelector('span.like-label');

    axios.post(url)
        .then(function (response) {
            spanCount.textContent = response.data.likes;
            if (icone.textContent === '\u{2661}') {
                icone.textContent = '\u{1f5a4}';
                likeLabel.textContent = "Je n'aime plus";
            }
            else {
                icone.textContent = '\u{2661}';
                likeLabel.textContent = "J'aime"
            }
        })
        .catch(function (error) {
            if (error.response.status === 403) {
                window.alert("Vous n'êtes pas autorisé à liker car vous n'êtes pas connecté!");
            }
            else {
                window.alert("Une erreur s'est produite,réessayez plus tard!");
            }
        });
}

document.querySelectorAll('a.js-like').forEach(function (link) {
    link.addEventListener('click', onClickBtnLike);
});