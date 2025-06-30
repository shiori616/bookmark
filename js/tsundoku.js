$(document).ready(() => {
  console.log("Tsundoku page loaded")
  loadTsundokuBooks()
})

function loadTsundokuBooks() {
  console.log("Loading tsundoku books...")

  $.ajax({
    url: "get_books.php?status=tsundoku",
    method: "GET",
    dataType: "json",
    success: (response) => {
      console.log("Tsundoku books loaded:", response)
      if (response.success) {
        displayBooks(response.books, "tsundoku")
      } else {
        $(".result").html('<div class="text-center p-4 text-red-600">データの読み込みに失敗しました。</div>')
      }
    },
    error: (xhr, status, error) => {
      console.error("Load error:", error)
      $(".result").html('<div class="text-center p-4 text-red-600">データの読み込み中にエラーが発生しました。</div>')
    },
  })
}

function displayBooks(books, type) {
  console.log("Displaying books:", books)

  if (!books || books.length === 0) {
    const message = type === "read" ? "読了済みの本がありません。" : "積読の本がありません。"
    $(".result").html(`<div class="text-center p-4 text-gray-600">${message}</div>`)
    return
  }

  const title = type === "read" ? "読了済み" : "積読"
  let html = `<div class="container mx-auto p-4"><h2 class="text-lg font-bold mb-4">${title} (${books.length}件)</h2>`

  books.forEach((book) => {
    const title = book.title || "タイトル不明"
    const authors = book.authors || "著者不明"
    const description = book.description || "説明なし"
    const imageUrl = book.image_url || "https://via.placeholder.com/128x192?text=No+Image"
    const savedDate = new Date(book.created_at).toLocaleDateString("ja-JP")

    html += `
            <div class="mb-6 p-4 border border-gray-200 rounded-lg bg-white">
                <div class="flex gap-4 h-48">
                    <!-- 書籍画像 -->
                    <div class="flex-shrink-0">
                        <img src="${imageUrl}" alt="${title}" class="w-32 h-48 object-cover rounded shadow-sm">
                    </div>
                    <!-- 書籍情報 -->
                    <div class="flex-grow flex flex-col h-48">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 flex-shrink-0">${title}</h3>
                        <p class="text-gray-700 mb-2 flex-shrink-0"><strong>著者:</strong> ${authors}</p>
                        <p class="text-gray-500 mb-2 flex-shrink-0"><strong>登録日:</strong> ${savedDate}</p>
                        <!-- スクロール可能な説明文エリア -->
                        <div class="flex-grow overflow-y-auto p-3">
                            <p class="text-gray-600 text-sm leading-relaxed">${description}</p>
                        </div>
                        <!-- ステータス表示 -->
                        <div class="flex gap-2 mt-2 flex-shrink-0">
                            <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-medium">
                                積読
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        `
  })

  html += "</div>"
  $(".result").html(html)
}
