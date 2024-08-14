<template>
    <h4><span class="admin-icon admin-icon-menu"></span>회원관리</h4>
        <div class="container my-5">
            <div class="col-md-10">
                <div class="card border-0 shadow-none">
                    <div class="card-body">
                        <!-- 여기부터 변경 -->
                        <UserRegisterForm/>
                        
                    </div>
                </div>
            </div>
        </div>
    </template>
    <script setup>
    import UserRegisterForm from "@/views/import/UserRegister.vue";
    

    </script>
    <style>
    h4 {
        position: relative;
        padding-bottom: 15px;
    }
    
    h4::after {
        content: "";
        display: block;
        width: 100%;
        height: 2px; 
        background-color: #f7f8fb;
        position: absolute;
        bottom: 0;
        left: 0;
    }
.search-btn2 {
    cursor: pointer;
    position: absolute;
    right: 5px;
    transform: translateY(55%);
    height: 40px;
    width: 40px;
    border: none;
    background-color: transparent;
    background-image: url('../../../../img/search.png');
    background-repeat: no-repeat;
    background-position: center;
    background-size: 20px 20px;
    font-size: 0;
}

    </style>