const elements = document.querySelectorAll('.accordion');

elements.forEach(element =>{
    let btn = element.querySelector('.accordion-title button');
    let icon = element.querySelector('.accordion-title button i');
    let answer = element.lastElementChild;
    let answers = document.querySelectorAll('.accordion .accordion-content');

    btn.addEventListener('click', ()=>{
        answers.forEach(ans =>{
            let ansIcon = ans.parentElement.querySelector('button i');
            if(answer !== ans){
                ans.classList.add('d-hidden');
                ansIcon.className = 'ri-add-circle-fill';
            }
        });

        answer.classList.toggle('d-hidden');
        icon.className === 'ri-add-circle-fill' ? icon.className = 'ri-indeterminate-circle-fill'
            : icon.className ='ri-add-circle-fill';
    });
});