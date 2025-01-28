let currentSettings = {
  seed: Math.floor(Math.random() * 1000000),
  language: "en_US",
  likes: 5.0,
  reviews: 4.7,
  page: 1,
}

document.addEventListener("DOMContentLoaded", () => {
  
  document.getElementById("seed").value = currentSettings.seed
  document.getElementById("language").value = currentSettings.language
  document.getElementById("likes").value = currentSettings.likes
  document.getElementById("reviews").value = currentSettings.reviews

  
  document.getElementById("language").addEventListener("change", updateBooks)
  document.getElementById("seed").addEventListener("change", updateBooks)
  document.getElementById("likes").addEventListener("input", updateLikesValue)
  document.getElementById("likes").addEventListener("change", updateBooks)
  document.getElementById("reviews").addEventListener("change", updateBooks)
  document.getElementById("randomSeed").addEventListener("click", randomizeSeed)

  
  loadBooks(true)
})

function updateLikesValue() {
  const value = document.getElementById("likes").value
  document.getElementById("likesValue").textContent = Number.parseFloat(value).toFixed(1)
}

function randomizeSeed() {
  const newSeed = Math.floor(Math.random() * 1000000)
  document.getElementById("seed").value = newSeed
  updateBooks()
}

function updateBooks() {
  currentSettings = {
    seed: Number.parseInt(document.getElementById("seed").value),
    language: document.getElementById("language").value,
    likes: Number.parseFloat(document.getElementById("likes").value),
    reviews: Number.parseFloat(document.getElementById("reviews").value),
    page: 1,
  }
  loadBooks(true)
}

function loadBooks(reset = false) {
  if (reset) {
      document.getElementById('booksTableBody').innerHTML = '';
      currentSettings.page = 1; 
  }

  document.getElementById('loading').classList.remove('d-none');

  const params = new URLSearchParams({
      seed: currentSettings.seed,
      language: currentSettings.language,
      likes: currentSettings.likes,
      reviews: currentSettings.reviews,
      page: currentSettings.page,
      batchSize: reset ? 20 : 10 
  });

  fetch(`ajax/get-books.php?${params}`)
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              renderBooks(data.data);
              currentSettings.page++;
          }
      })
      .finally(() => {
          document.getElementById('loading').classList.add('d-none');
      });
}

function renderBooks(books) {
  const tbody = document.getElementById("booksTableBody")
  books.forEach((book) => {
    const tr = document.createElement("tr")
    tr.innerHTML = `
            <td>${book.index}</td>
            <td>${book.isbn}</td>
            <td>${book.title}</td>
            <td>${book.authors.join(", ")}</td>
            <td>${book.publisher}</td>
        `

    tr.addEventListener("click", () => toggleBookDetails(tr, book))
    tbody.appendChild(tr)
  })
}

function toggleBookDetails(tr, book) {
  const existingDetails = tr.nextElementSibling
  if (existingDetails && existingDetails.classList.contains("expanded-details")) {
    existingDetails.remove()
    return
  }

  const detailsRow = document.createElement("tr")
  detailsRow.classList.add("expanded-details")
  detailsRow.innerHTML = `
        <td colspan="5">
            <div class="row">
                <div class="col-md-3">
                    <img src="${book.cover}" alt="${book.title}" class="book-cover img-fluid">
                </div>
                <div class="col-md-9">
                    <h4>${book.title}</h4>
                    <p class="text-muted">by ${book.authors.join(", ")}</p>
                    <div class="likes-section mb-3">
                        <strong>Likes:</strong> ${book.likes}
                    </div>
                    <div class="reviews-section">
                        <h5>Reviews (${book.reviews.length})</h5>
                        ${book.reviews
                          .map(
                            (review) => `
                            <div class="review-item">
                                <p>${review.text}</p>
                                <small class="text-muted">— ${review.author}, ${review.company}</small>
                            </div>
                        `,
                          )
                          .join("")}
                    </div>
                </div>
            </div>
        </td>
    `

  tr.parentNode.insertBefore(detailsRow, tr.nextSibling)
}

