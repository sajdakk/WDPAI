function showToast(event) {
    // Use your preferred method to show a toast message
    // Example using a simple alert:
    alert("You have to log in");

    // Stop the event propagation to prevent the parent form's onclick from being triggered
    event.stopPropagation();
}

async function search(event) {
    event.preventDefault();

    const title = document.querySelector('#title').value.trim();
    const authorName = document.querySelector('#author_name').value.trim();
    const authorSurname = document.querySelector('#author_surname').value.trim();
    const bookContainer = document.querySelector(".news");
    const header = document.querySelector("#may-interest-you");



    try {
        const formData = new FormData();
        if (title != '') {
            formData.append('title', title);
        }

        if (authorName != '') {
            formData.append('name', authorName);
        }

        if (authorSurname != '') {
            formData.append('surname', authorSurname);
        }


        const response = await fetch('/search', {
            method: 'POST',
            body: formData,
        });

        if (title === '' && authorName === '' && authorSurname === '') {
            header.style.display = "initial";
        } else {
            header.style.display = "none";

        }

        const data = await response.json();
        bookContainer.innerHTML = "";
        loadProjects(data);



    } catch (error) {
        alert(error.message);
    }



    function loadProjects(projects) {
        projects.forEach(project => {
            createProject(project);
        });
    }

    function roundToTOne(num) {
        return +(Math.round(num + "e+1") + "e-1");
    }


    function createProject(project) {
        const buildButton = () => {
            if (project.isFavorite == null) {
                return `
                    <button type="button" onclick="showToast(event)">
                        <i class="material-icons">favorite_outline</i>
                    </button>
                `;
            }

            return `
                <button onclick="toggleFavorite(event, ${project.id});">
                    <i class="material-icons">
                        ${project.isFavorite ? 'favorite' : 'favorite_outline'}
                    </i>
                </button>
            `;
        }

        const html = `
            <div class="news-container" onclick="routeToDetails('${project.id}')">
                <img class="news-image" src="${'/public/uploads/' + project.image}" alt="News Image 1">
                <div class="news-description">
                    <div class="card-header">
                        <div class="title">
                            <div class="inter-semibold-16">
                                ${project.title}
                            </div>
                            ${buildButton()}
                        </div>
                        <div class="inter-regular-12">
                            ${project.authorsString}
                        </div>
                    </div >
                    <div class="extra-info">
                        <div class="score">
                            <i class="material-icons">star_border</i>
                            <div class="inter-light-14">
                                ${roundToTOne(project.average_rate)}/5
                            </div>
                        </div>
                        <div class="inter-extra-light-14">
                            ${project.rate_count} reviews
                        </div>
                    </div>
                </div>
            </div>
        `;

        const bookContainer = document.querySelector(".news");

        bookContainer.innerHTML += html;

    }

}