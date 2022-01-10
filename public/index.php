<?php
include '../config/config.php';
session_start();

//Login
if (preg_match('/^\/admin-login\/?/i', $_SERVER['REQUEST_URI'])) {
    if (isset($_SESSION['admin'])) {
        header('Location: /admin');
        exit();
    }
    $GLOBALS['aciklama'] = TITLE_OAS;
    include '../src/Domain/Admin-LoginPage/Admin-LoginPage.php';
} elseif (preg_match('/^\/login\/?/i', $_SERVER['REQUEST_URI'])) {
    if (isset($_SESSION['mezun'])) {
        header('Location: /home');
        exit();
    }
    $GLOBALS['aciklama'] = TITLE_OAS;
    include '../src/Domain/GirisSayfasi/Giris.php';
}
elseif (preg_match('/^\/api\/signin/i', $_SERVER['REQUEST_URI'])) {
    $GLOBALS['aciklama'] = TITLE_OAS;
    include '../src/Domain/GirisSayfasi/GirisKontrol.php';
} elseif (preg_match('/^\/api\/signup/i', $_SERVER['REQUEST_URI'])) {
    $GLOBALS['aciklama'] = TITLE_OAS;
    include '../src/Domain/GirisSayfasi/KayıtKontrol.php';
} elseif (preg_match('/^\/api\/forgot/i', $_SERVER['REQUEST_URI'])) {
    $GLOBALS['aciklama'] = TITLE_OAS;
    include '../src/Domain/GirisSayfasi/SifremiUnuttumKontrol.php';
}

elseif (preg_match('/^\/api\/adminsignin/i', $_SERVER['REQUEST_URI'])) {
    $GLOBALS['aciklama'] = TITLE_OAS;
    include '../src/Domain/Admin-LoginPage/SignInAdminController.php';
} elseif (preg_match('/^\/api\/adminforgot/i', $_SERVER['REQUEST_URI'])) {
    $GLOBALS['aciklama'] = TITLE_OAS;
    include '../src/Domain/Admin-LoginPage/ForgotPasswordAdminController.php';
}

elseif (preg_match('/^\/api\/log-out\/?$/i', $_SERVER['REQUEST_URI'])) {
    include '../src/Domain/GirisSayfasi/CikisKontrol.php';
    exit(); 
}

else if (isset($_SESSION['mezun'])) {
    if (preg_match('/^\/home/i', $_SERVER['REQUEST_URI']) || preg_match('/^\/$/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_OAS;
        include '../src/Domain/Anasayfa/Anasayfa.php';
    }

    elseif (preg_match('/^\/myprofile\/edit/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_MY_PROFILE;
        include '../src/Domain/Profilim/ProfilDüzenleme.php';
    } elseif (preg_match('/^\/myprofile/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_MY_PROFILE;
        include '../src/Domain/Profilim/Profilim.php';
    }
    elseif (preg_match('/^\/api\/myprofile\/edit\/?$/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_MY_PROFILE;
        include '../src/Domain/Profilim/ProfilDüzenlemeKontrol.php';
    } elseif (preg_match('/^\/api\/myprofile\/changepassword\/?$/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_MY_PROFILE;
        include '../src/Domain/Profilim/SifreKontrol.php';
    } elseif (preg_match('/^\/api\/myprofile\/changeprivacy/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_MY_PROFILE;
        include '../src/Domain/Profilim/GizlilikKontrol.php';
    } elseif (preg_match('/^\/api\/myprofile\/delete\/?$/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_MY_PROFILE;
        include '../src/Domain/Profilim/HesapSilme.php';
    }

    elseif (preg_match('/^\/alumni\/profile/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_ALUMNI_PROFILE;
        include '../src/Domain/Mezun/MezunProfil.php';
    } elseif (preg_match('/^\/alumni/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_ALUMNI;
        include '../src/Domain/Mezun/Mezun.php';
    }

    elseif (preg_match('/^\/eventdetails\?eventId=?/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_EVENTS;
        include '../src/Domain/Etkinlik/EtkinlikDetay.php';
    } elseif (preg_match('/^\/event\/?/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_EVENTS;
        include '../src/Domain/Etkinlik/Etkinlik.php';
    } elseif (preg_match('/^\/my-event\/?/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_MY_EVENTS;
        include '../src/Domain/Etkinlik/Etkinlik.php';
    } elseif (preg_match('/^\/api\/event\?/i', $_SERVER['REQUEST_URI'])) {
        include '../src/Domain/Etkinlik/EtkinlikKontrol.php';
    } elseif (preg_match('/^\/api\/alumni-event\/?$/i', $_SERVER['REQUEST_URI'])) {
        include '../src/Domain/Etkinlik/MezunEtkinlikKontrol.php';
    }
    
    elseif (preg_match('/^\/job\/?$/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_JOB;
        include '../src/Domain/isIlani/isIlani.php';
    } elseif (preg_match('/^\/jobdetails\/?/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_JOB;
        include '../src/Domain/isIlani/isIlaniDetay.php';
    } elseif (preg_match('/^\/myjob\/?$/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_MYJOB;
        include '../src/Domain/isIlani/isIlanlarim.php';
    } elseif (preg_match('/^\/myjobdetails\/?/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_MYJOB;
        include '../src/Domain/isIlani/isIlanlarimDetay.php';
    } elseif (preg_match('/^\/addjob\/?$/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_ADDJOB;
        include '../src/Domain/isIlani/isIlaniEkle.php';
    } elseif (preg_match('/^\/editmyjob\/?/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_EDITJOB;
        include '../src/Domain/isIlani/isIlanlarimDüzenleme.php';
    }

    elseif (preg_match('/^\/deleteJob\/?/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_MYJOB;
        include '../src/Domain/isIlani/isIlaniSilKontrol.php';
    } elseif (preg_match('/^\/searchJob\/?/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_MYJOB;
        include '../src/Domain/isIlani/AramaKontrol.php';
    } elseif (preg_match('/^\/searchAllJob\/?/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_JOB;
        include '../src/Domain/isIlani/isIlaniAramaKontrol.php';
    } else {
        $GLOBALS['aciklama'] = TITLE_NOT_FOUND;
        http_response_code(404);
        include '../src/araclar/Degisken.php';
        includeWithVariables('../src/sablonlar/Baslik.php', array(
            'index' => '/css/Alumni/index.css'
        ));
        include '../src/sablonlar/nav.php';
        include '../src/Domain/Genel_Sayfa/sayfa_bulunamadi.php';
        include_once '../src/sablonlar/AltBilgi.php';
        include_once '../src/sablonlar/GenelScript.php';
    }
}
else if (isset($_SESSION['admin'])) {
    if (preg_match('/^\/admin$/i', $_SERVER['REQUEST_URI']) || preg_match('/^\/$/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_MY_PROFILE;
        include '../src/Domain/Admin-Home/Admin-HomePage.php';
    }
    elseif (preg_match('/^\/adminprofile\/edit/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_MY_PROFILE;
        include '../src/Domain/Admin-MyProfile/Admin-EditMyProfilePage.php';
    } elseif (preg_match('/^\/adminprofile/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_MY_PROFILE;
        include '../src/Domain/Admin-MyProfile/Admin-MyProfilePage.php';
    }
    elseif (preg_match('/^\/api\/adminprofile\/edit\/?$/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_MY_PROFILE;
        include '../src/Domain/Admin-MyProfile/Admin-EditMyProfileController.php';
    } elseif (preg_match('/^\/api\/adminprofile\/changepassword\/?$/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_MY_PROFILE;
        include '../src/Domain/Admin-MyProfile/AdminChangePasswordController.php';
    }

    elseif (preg_match('/^\/admin\/alumnilist\/?$/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_ADMIN_MANAGE_ALUMNI;
        include '../src/Domain/Admin-ManageAlumni/Admin-AlumniListPage.php';
    } elseif (preg_match('/^\/admin\/deleteAlumni\/?$/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_ADMIN_MANAGE_ALUMNI;
        include '../src/Domain/Admin-ManageAlumni/Admin-DeleteAlumniController.php';
    } elseif (preg_match('/^\/admin\/approveAlumni\/?$/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_ADMIN_MANAGE_ALUMNI;
        include '../src/Domain/Admin-ManageAlumni/Admin-ApproveAlumniController.php';
    } elseif (preg_match('/^\/admin\/deleteMultipleAlumni\/?$/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_ADMIN_MANAGE_ALUMNI;
        include '../src/Domain/Admin-ManageAlumni/Admin-deleteMultipleAlumni.php';
    } elseif (preg_match('/^\/admin\/editalumni\/?/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_ADMIN_EDIT_ALUMNI_PROFILE;
        include '../src/Domain/Admin-ManageAlumni/Admin-EditAlumniProfilePage.php';
    } elseif (preg_match('/^\/admin\/searchAlumniName\/?/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_ADMIN_MANAGE_ALUMNI;
        include '../src/Domain/Admin-ManageAlumni/Admin-FilterController.php';
    }

    elseif (preg_match('/^\/admin\/event\/?$/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_ADMIN_EVENTS;
        include '../src/Domain/Admin-ManageEvent/Admin-EventPage.php';
    } elseif (preg_match('/^\/admin\/update\/event\/?/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_ADMIN_UPDATE_EVENTS;
        include '../src/Domain/Admin-ManageEvent/Admin-UpdateEventPage.php';
    } elseif (preg_match('/^\/admin\/create\/event\/?/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_ADMIN_CREATE_EVENTS;
        include '../src/Domain/Admin-ManageEvent/Admin-CreateEventPage.php';
    } elseif (preg_match('/^\/admin\/invite\/alumni\/?/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_ADMIN_INVITE_ALUMNI;
        include '../src/Domain/Admin-ManageEvent/Admin-InviteAlumniPage.php';
    } elseif (preg_match('/^\/admin\/delete\/event\/?$/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_ADMIN_EVENTS;
        include '../src/Domain/Admin-ManageEvent/Admin-DeleteEventController.php';
    } elseif (preg_match('/^\/admin\/invite\/function\/?$/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_ADMIN_EVENTS;
        include '../src/Domain/Admin-ManageEvent/Admin-InviteAlumniController.php';
    } elseif (preg_match('/^\/admin\/search\/event\/?$/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_ADMIN_INVITE_ALUMNI;
        include '../src/Domain/Admin-ManageEvent/Admin-EventSearchController.php';
    } elseif (preg_match('/^\/admin\/search\/invite\/alumni\/?/i', $_SERVER['REQUEST_URI'])) {
        $GLOBALS['aciklama'] = TITLE_ADMIN_INVITE_ALUMNI;
        include '../src/Domain/Admin-ManageEvent/Admin-inviteAlumniSearchController.php';
    } else {
        $GLOBALS['aciklama'] = TITLE_NOT_FOUND;
        http_response_code(404);
        include '../src/araclar/Degisken.php';
        includeWithVariables('../src/sablonlar/Baslik.php', array(
            'index' => '/css/Alumni/index.css'
        ));
        include '../src/Domain/Genel_Sayfa/admin_sayfasi_bulunamadi.php';
    }
}
elseif (strpos($_SERVER['REQUEST_URI'], 'admin') !== false) {
    header('Location:/admin-login');
    exit;
} else {
    header('Location: /login');
    exit;
}
