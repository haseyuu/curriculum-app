document.addEventListener('DOMContentLoaded', function () {
    const btnPosts = document.getElementById('btn-posts');
    const btnLikes = document.getElementById('btn-likes');
    const postsList = document.getElementById('posts-list');
    const likesList = document.getElementById('likes-list');
    const postMoreBtn = document.getElementById('post-more');
    const likeMoreBtn = document.getElementById('like-more');
    const loadMoreBtn = document.getElementById('load-more');
    const avatarPreview = document.getElementById('avatarPreview');
    const avatarInput = document.getElementById('avatarInput');
    const cropDoneBtn = document.getElementById('cropDoneBtn');
    const form = document.getElementById('profileForm');

    let cropper;


    // --- 投稿 / いいね切り替え (user_page 用) ---
    if (btnPosts && btnLikes && postsList && likesList) {
        btnPosts.addEventListener('click', () => {
            // alert('post');
            postsList.style.display = 'block';
            likesList.style.display = 'none';
        });

        btnLikes.addEventListener('click', () => {
            // alert('favo');
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

    
    document.addEventListener('click', function(e) {
        if (e.target.matches('#btn-follow')) {
            const btn = e.target;
            const id = btn.dataset.id;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            if (btn.value=='0') {
                if (!confirm('本当に解除しますか？')) return;

                fetch(`/user/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        btn.value='1';
                        btn.textContent='フォローする';
                    } else {
                        alert('フォロー解除に失敗しました');
                    }
                })
                .catch(err => console.error(err));

            } else {
                if (!confirm('本当にフォローしますか？')) return;
                
                fetch(`/user/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        btn.value='0';
                        btn.textContent='フォロー解除';
                    } else {
                        alert(data.message);
                    }
                })
                .catch(err => console.error(err));
            }
        }
    });

    document.addEventListener('click', function(e) {
        if (e.target.matches('.btn-favo')) {
            const btn = e.target;
            const id = btn.dataset.id;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            // alert('test');
            if (btn.dataset.liked=='0') {
                fetch(`/favo/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // alert(data.count);
                        btn.dataset.liked='1';
                        btn.textContent='★';
                        btn.nextElementSibling.textContent = data.count;
                    } else {
                        alert(data.message);
                    }
                })
                .catch(err => console.error(err));

            } else {

                fetch(`/favo/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // alert(data.count);
                        btn.dataset.liked='0';
                        btn.textContent='☆';
                        btn.nextElementSibling.textContent = data.count;
                    } else {
                        alert(data.message);
                    }
                })
                .catch(err => console.error(err));
            }
        }
    });

    if(avatarPreview){
        avatarPreview.addEventListener('click', function() {
            if (!cropper) {
                avatarInput.click();
            }
        });
    }
    
    if(avatarInput){
        avatarInput.addEventListener('change', function(e){
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(event){
                avatarPreview.src = event.target.result;

                if (cropper) cropper.destroy();
                cropper = new Cropper(avatarPreview, {
                    aspectRatio: 1,
                    viewMode: 1,
                    autoCropArea: 1,
                });

                cropDoneBtn.style.display = 'inline-block'; // 完了ボタン表示
            };
            reader.readAsDataURL(file);
        });
    }
    
    if(cropDoneBtn){
        cropDoneBtn.addEventListener('click', function(){
            if (!cropper) return;

            cropper.getCroppedCanvas().toBlob((blob) => {
                const file = new File([blob], 'avatar.png', { type: 'image/png' });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                avatarInput.files = dataTransfer.files;

                const url = URL.createObjectURL(blob);
                avatarPreview.src = url;

                cropper.destroy();
                cropper = null;
                cropDoneBtn.style.display = 'none';
            });
        });
    }
    
    if(form){
        form.addEventListener('submit', function(e){
            if (cropper) {
                e.preventDefault();
                cropper.getCroppedCanvas().toBlob((blob) => {
                    const file = new File([blob], 'avatar.png', { type: 'image/png' });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    avatarInput.files = dataTransfer.files;

                    cropper.destroy();
                    cropper = null;
                    cropDoneBtn.style.display = 'none';
                    form.submit();
                });
            }
        });
    }
      
    document.addEventListener('click', function(e) {
        if (e.target.matches('.btn-delete-user')) {
            e.preventDefault();
            const btn = e.target;
            const id = btn.dataset.id;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            if(!confirm('本当に削除しますか？'))return;
            fetch(`/delete/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                    } else {
                        alert(data.message);
                    }
                })
                .catch(err => console.error(err));
        }
    });

});