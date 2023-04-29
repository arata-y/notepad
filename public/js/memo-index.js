let memos = document.getElementsByClassName('memos');
let deletebuttons = document.getElementsByClassName('delete');

for(let mi = 0; mi < memos.length; mi++)
{
    memos[mi].addEventListener('mouseover',() =>
    {
        deletebuttons[mi].style.opacity = 1;
    });

    deletebuttons[mi].addEventListener('mouseover',() =>
    {
        deletebuttons[mi].style.opacity = 1;
    });

    memos[mi].addEventListener('mouseleave',() => 
    {
        deletebuttons[mi].style.opacity = 0;
    });

    deletebuttons[mi].addEventListener('mouseleave',() => 
    {
        deletebuttons[mi].style.opacity = 0;
    });
}