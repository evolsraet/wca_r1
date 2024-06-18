<template>
    <div class="row justify-content-center my-5">
        <div class="col-md-10">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form @submit.prevent="submitForm">
                        <div class="mb-3">
                            <label for="user-title" class="form-label"
                                >이름</label
                            >
                            <input
                                v-model = "user.name"
                                type="text"
                                class="form-control"
                            />
                            <div class="text-danger mt-1">
                                {{ errors.name }}
                            </div>
                            <div class="text-danger mt-1">
                                <div v-for="message in validationErrors?.name">
                                    {{ message }}
                                </div>
                            </div>

                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input
                                v-model = "user.email"
                                type="email"
                                class="form-control"
                            />
                            <div class="text-danger mt-1">
                                {{ errors.email }}
                            </div>
                            <div class="text-danger mt-1">
                                <div v-for="message in validationErrors?.email">
                                    {{ message }}
                                </div>
                            </div>
                            
                        </div>
                        <!-- Role 
                        <div class="mb-3">
                            <label for="user-category" class="form-label" >
                                권한
                            </label>
                            <v-select id="roleSelect"
                                v-model="user.role"
                                multiple
                                :options="roleList"
                                :reduce="(role) => role.id"
                                label="name"
                                class="form-control"
                                @click="test"
                            />
                             <div class="text-danger mt-1">
                                {{ errors.role_id }}
                            </div>
                            <div class="text-danger mt-1">
                                <div
                                    v-for="message in validationErrors?.role_id"
                                >
                                    {{ message }}
                                </div>
                            </div>
                           
                        </div>-->
                        <div class="mb-3">
                            <label for="email" class="form-label">승인여부</label>
                            <div class="text-start status-selector">
                                <input type="radio" name="status" v-model="user.status" value="ok" id="ok">
                                <label for="ok" class="mx-2">정상</label>
                                <input type="radio" name="status" value="ask" v-model="user.status" id="ask">
                                <label for="ask">심사중</label>
                                <input type="radio" name="status" value="reject" v-model="user.status" id="reject">
                                <label for="reject" class="mx-2">거절</label>
                            </div>
                            
                        </div>
                        <!-- Buttons -->
                        <div class="mt-4">
                            <button
                                :disabled="isLoading"
                                class="btn btn-primary"
                            >
                                <div v-show="isLoading" class=""></div>
                                <span v-if="isLoading">Processing...</span>
                                <span v-else>Save</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { onMounted, reactive, watchEffect, ref } from "vue";
import { useRoute } from "vue-router";
import useRoles from "@/composables/roles";
import useUsers from "@/composables/users";
import { useForm, useField, defineRule } from "vee-validate";
import { required, min } from "@/validation/rules";
defineRule("required", required);
defineRule("min", min);

const route = useRoute();
const { roleList, getRoleList } = useRoles();

const {
    updateUser,
    getUser,
    validationErrors,
    isLoading,
} = useUsers();

// Define a validation schema
const schema = {
    name: "required",
    email: "required",
    password: "min:8",
};

// Create a form context with the validation schema
const { validate, errors, resetForm } = useForm({ validationSchema: schema });
// Define actual fields for validation
const { value: name } = useField("name", null, { initialValue: "" });
const { value: email } = useField("email", null, { initialValue: "" });
const { value: status } = useField("status", null, { initialValue: "" });

const user = reactive({
    name,
    email,
    status,
    //role
});

onMounted(async () => {
    const response = await getUser(route.params.id);
    await getRoleList();

    /** 
    for (const roleName of response.roles) {
        const findRoleId = roleList.value.find(role => role.name === roleName);
        user.role = findRoleId.id;
    }*/

    user.name = response.name;
    user.email = response.email;
    user.status = response.status;
});

function submitForm() {
    validate().then((form) => {
        console.log(user);
        console.log(JSON.stringify(user));
        if (form.valid) updateUser(user,route.params.id);
    });
}

</script>
