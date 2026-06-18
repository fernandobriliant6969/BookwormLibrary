function tampilLoadingAnimation(form) {
    const button = form.querySelector('button[type="submit"]');
    
    if(button){
        const textButton = button.querySelector('#text-button');
        const animasi = button.querySelector('#spinner-loading');
        const textLoading = button.querySelector('#text-loading');
        
        button.disabled = true;
        if(textButton) textButton.classList.add('d-none');
        if(animasi) animasi.classList.remove('d-none');
        if(textLoading) textLoading.classList.remove('d-none');
        
    }
}   