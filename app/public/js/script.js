document.addEventListener('DOMContentLoaded', function () {
    const btnPosts = document.getElementById('btn-posts');
    const btnLikes = document.getElementById('btn-likes');
    const postsList = document.getElementById('posts-list');
    const likesList = document.getElementById('likes-list');
    const postMoreBtn = document.getElementById('post-more');
    const likeMoreBtn = document.getElementById('like-more');
    const loadMoreBtn = document.getElementById('load-more');

    // --- 投稿 / いいね切り替え (user_page 用) ---
    if (btnPosts && btnLikes && postsList && likesList) {
        btnPosts.addEventListener('click', () => {
            postsList.style.display = 'block';
            likesList.style.display = 'none';
        });

        btnLikes.addEventListener('click', () => {
            postsList.style.display = 'none';
            likesList.style.display = 'block';
        });
    }

    // --- 投稿「もっと見る」 (user_page 用) ---
    if (postMoreBtn) {
        postMoreBtn.addEventListener('click', function(){
            let button = this;
            let url = button.dataset.nextPage;

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
            .then(res => res.text())
            .then(html => {
                let temp = document.createElement('div');
                temp.innerHTML = html;

                temp.querySelectorAll('#posts-list > .card')
                    .forEach(card => postsList.appendChild(card));

                let next = temp.querySelector('#post-more')?.dataset.nextPage;
                if(next){
                    button.dataset.nextPage = next;
                } else {
                    button.style.display = 'none';
                }
            });
        });
    }

    // --- いいね「もっと見る」 (user_page 用) ---
    if (likeMoreBtn) {
        likeMoreBtn.addEventListener('click', function(){
            let button = this;
            let url = button.dataset.nextPage;

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
            .then(res => res.text())
            .then(html => {
                let temp = document.createElement('div');
                temp.innerHTML = html;

                temp.querySelectorAll('#likes-list > .card')
                    .forEach(card => likesList.appendChild(card));

                let next = temp.querySelector('#like-more')?.dataset.nextPage;
                if(next){
                    button.dataset.nextPage = next;
                } else {
                    button.style.display = 'none';
                }
            });
        });
    }

    // --- 投稿「もっと見る」 (main 用) ---
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function(){
            const button = this;
            const url = button.dataset.nextPage;

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
            .then(res => res.text())
            .then(html => {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;

                // カードのみ追加
                tempDiv.querySelectorAll('.card').forEach(card => document.getElementById('posts-container').appendChild(card));

                // 次ページボタン更新
                const nextPageBtn = tempDiv.querySelector('#load-more');
                if (nextPageBtn) {
                    button.dataset.nextPage = nextPageBtn.dataset.nextPage;
                } else {
                    button.style.display = 'none';
                }
            });
        });
    }

    document.addEventListener('click', function(e) {
        if (e.target.matches('.btn-delete-post')) {
            if (!confirm('本当に削除しますか？')) return;

            const btn = e.target;
            const postId = btn.dataset.id;
            const card = btn.closest('.card');
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/posts/${postId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    card.remove();
                } else {
                    alert('削除に失敗しました');
                }
            })
            .catch(err => console.error(err));
        }
    });
});