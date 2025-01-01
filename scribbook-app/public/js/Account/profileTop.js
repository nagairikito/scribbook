let modal = document.querySelector('.modal');
let modalEditProfile = document.querySelector('.modal-contents.edit-profile');
let modalPrivacySetting = document.querySelector('.modal-contents.privacy-setting');


const EDIT_PROFILE = 1;
const PRIVACY_SETTING = 2;

/**
 * モーダルを開く
 * @param {*} $contentsType 
 * @returns 
 */
function openModal($contentsType) {
    modal.classList.replace('close', 'open');
    console.log($contentsType);
    if($contentsType == EDIT_PROFILE) {
        modalEditProfile.classList.remove('display-none');
        return;
    }
    if($contentsType == PRIVACY_SETTING) {
        modalPrivacySetting.classList.remove('display-none');
        return
    }
    modal.classList.replace('open', 'close');
    return;

}

/**
 * モーダルを閉じる
 * @param {*} $contentsType 
 * @returns 
 */
function closeModal($contentsType) {
    modal.classList.replace('open', 'close');
    
    if($contentsType == EDIT_PROFILE) {
        modalEditProfile.classList.add('display-none');
        return;
    }
    if($contentsType == PRIVACY_SETTING) {
        modalPrivacySetting.classList.add('display-none');
        return
    }
    modal.classList.replace('close', 'open');
    return;

}

