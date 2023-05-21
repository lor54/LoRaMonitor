async function changeLanguage(lang) {
    await fetch("/actions/changeLanguage.php?lang=" + lang);
    document.location.reload();
}