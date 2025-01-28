
let currentSettings = {
  seed: Math.floor(Math.random() * 1000000),
  language: "en_US",
  likes: 5.0,
  reviews: 4.7,
  offset: 0,
  batchSize: 20,
  totalBooks: 0,
}

let booksIterator = null
let scrollIterator = null

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

  
  initInfiniteScroll()
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
    ...currentSettings,
    seed: Number.parseInt(document.getElementById("seed").value),
    language: document.getElementById("language").value,
    likes: Number.parseFloat(document.getElementById("likes").value),
    reviews: Number.parseFloat(document.getElementById("reviews").value),
    offset: 0,
    totalBooks: 0,
  }
  document.getElementById("booksTableBody").innerHTML = ""
  booksIterator = null
  scrollIterator = null
  initInfiniteScroll()
}

async function* getBooks() {
  while (true) {
    const params = new URLSearchParams({
      seed: currentSettings.seed,
      language: currentSettings.language,
      likes: currentSettings.likes,
      reviews: currentSettings.reviews,
      offset: currentSettings.offset,
      batchSize: currentSettings.batchSize,
    })

    const response = await fetch(`ajax/get-books.php?${params}`)
    const data = await response.json()

    if (data.success && data.data.length > 0) {
      yield data.data
      currentSettings.offset += data.data.length
    } else {
      return
    }
  }
}

async function* scrollEvents() {
  const container = document.documentElement
  while (true) {
    yield await new Promise((resolve) => {
      const checkScroll = () => {
        if (container.scrollHeight - container.scrollTop <= container.clientHeight + 100) {
          window.removeEventListener("scroll", checkScroll)
          resolve()
        }
      }
      window.addEventListener("scroll", checkScroll)
    })
  }
}

async function initInfiniteScroll() {
  if (!booksIterator) {
    booksIterator = getBooks()
  }
  if (!scrollIterator) {
    scrollIterator = scrollEvents()
  }

  try {
    const { value: books, done: booksDone } = await booksIterator.next()
    if (!booksDone) {
      renderBooks(books)
      await scrollIterator.next()
      initInfiniteScroll()
    }
  } catch (error) {
    console.error("Error loading books:", error)
  }
}

function renderBooks(books) {
  const tbody = document.getElementById("booksTableBody")
  books.forEach((book, index) => {
    const tr = document.createElement("tr")
    const bookIndex = currentSettings.totalBooks + index + 1
    tr.innerHTML = `
      <td>${bookIndex}</td>
      <td>${book.isbn}</td>
      <td>${book.title}</td>
      <td>${book.authors.join(", ")}</td>
      <td>${book.publisher}</td>
    `

    tr.addEventListener("click", () => toggleBookDetails(tr, book))
    tbody.appendChild(tr)
  })
  currentSettings.totalBooks += books.length
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




