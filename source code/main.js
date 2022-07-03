function openNav() {
    document.getElementById("mySidepanel").style.width = "30%";
    document.getElementById("mySidepanel").style.height = "100%";
}

function closeNav() {
    document.getElementById("mySidepanel").style.width = "0";
}

function countdown(){
    let duration = 5;
    let countDown = 5;
    let id = setInterval(() => {

        countDown --;
        if (countDown >= 0) {
            document.getElementById("counter").innerHTML = countDown;
        }
        if (countDown == -1) {
            clearInterval(id);
            window.location.href = 'login.php';
        }

    }, 1000);
}
function del(){
    return confirm('Bạn có chắc muốn xóa');
}

