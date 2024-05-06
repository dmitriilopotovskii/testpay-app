<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import {useForm} from 'vee-validate';
import * as yup from 'yup';
import valid from 'card-validator'
import {computed} from "vue";


const props = defineProps({errors: Object})
const {values, errors, defineField} = useForm({
    validationSchema: yup.object({
        dollar: yup.number().positive().required(),
        rouble: yup.number().positive().required(),
        cardNumber: yup.string()
            .label('Card number')
            .max(16)
            .min(16)
            .required(),
        cvc: yup.string()
            .label('CVC/CVV number')
            .max(3)
            .min(3)
            .required(),
        month: yup.string()
            .label('Month')
            .max(2)
            .min(2)
            .test('test-number',
                'Month is invalid',
                value => valid.expirationMonth(value).isValid)
            .required(),
        year: yup.string()
            .label('Year')
            .max(2)
            .min(2)
            .test('test-number',
                'Year is invalid',
                value => valid.expirationYear(value).isValid)
            .required(),
    }),
});

const [rouble, roubleAttrs] = defineField('rouble')
const [dollar, dollarAttrs] = defineField('dollar')
const [cardNumber, cardNumberAttrs] = defineField('cardNumber')
const [cvc, cvcAttrs] = defineField('cvc')
const [month, monthAttrs] = defineField('month')
const [year, yearAttrs] = defineField('year')
const submit = () => {
    console.log(rouble.value, cardNumber.value, cvc.value, month.value, year.value)
}

const expirationDate = computed(() => {
    return valid.expirationDate(month.value + '/' + year.value).isValid
})
const convertRoubleToDollar = (value) => {
    dollar.value = Math.round(value / 15 * 100) / 100
}
const convertDollarToRouble = (value) => {
    rouble.value = Math.round(value * 15 * 100) / 100
}

</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">You're logged in!</div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<style scoped>
.cardbackround {
    background-image: url("data:image/svg+xml,%3Csvg width='324' height='209' viewBox='0 0 324 209' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cmask id='mask0_1_502' style='mask-type:alpha' maskUnits='userSpaceOnUse' x='0' y='0' width='324' height='209'%3E%3Crect y='0.138672' width='324' height='208' rx='12' fill='white'/%3E%3C/mask%3E%3Cg mask='url(%23mask0_1_502)'%3E%3Cellipse opacity='0.2' cx='295.301' cy='-16.8613' rx='160.5' ry='113' fill='%23004FC4'/%3E%3Cellipse opacity='0.2' cx='29.3008' cy='225.139' rx='160.5' ry='113' fill='%23004FC4'/%3E%3C/g%3E%3C/svg%3E ");
}

.cardbackroundcvc {
    background-image: url("data:image/svg+xml,%3Csvg width='224' height='200' viewBox='0 0 224 200' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Crect width='224' height='200' rx='12' fill='%23EBEBF0'/%3E%3Crect opacity='0.3' y='20' width='224' height='40' fill='%23C7C9D9'/%3E%3C/svg%3E%0A");
}

</style>
