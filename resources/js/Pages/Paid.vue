<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router} from '@inertiajs/vue3';
import InputError from "@/Components/InputError.vue";
import {useForm} from 'vee-validate';
import * as yup from 'yup';
import valid from 'card-validator'
import {computed, ref} from "vue";


const props = defineProps({
    errors: Object,
    success: Object,
    cards: Object
})
const { errors, defineField} = useForm({
    validationSchema: yup.object({
        dollar: yup.number().positive().required(),
        rouble: yup.number().positive().required().min(15),
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
const remember = ref(false)
const [rouble = 0, roubleAttrs] = defineField('rouble')
const [dollar = 0, dollarAttrs] = defineField('dollar')
const [cardNumber, cardNumberAttrs] = defineField('cardNumber')
const [cvc, cvcAttrs] = defineField('cvc')
const [month = '00', monthAttrs] = defineField('month')
const [year = '00', yearAttrs] = defineField('year')
const submit = () => {
    router.post('/paid', {
        rouble: rouble.value,
        cardNumber: cardNumber.value,
        cvc: cvc.value,
        month: month.value,
        year: year.value,
        remember: remember.value
    })
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
    <Head title="Paid"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Paid</h2>
        </template>


        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div
                v-if="success"
                class="  flex justify-center mb-4 w-1/4 rounded bg-emerald-500 px-4 py-2 text-white"
            >
                <div>{{ success }}</div>
            </div>
            <div v-if="!(Object.keys(props.errors).length === 0)"
                 class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50"
                 role="alert">

                <svg class="flex-shrink-0 inline w-4 h-4 me-3 mt-[2px]" aria-hidden="true"
                     xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <span class="sr-only">Danger</span>
                <div>
                    <span class="font-medium">Убедитесь, что эти требования выполнены(Backend validation):</span>
                    <ul v-for="error in props.errors" class="mt-1.5 list-disc list-inside">
                        <li>{{ error }}</li>
                    </ul>
                </div>
            </div>
            <form @submit.prevent="submit" class="mt-6 space-y-6">
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div
                            class="flex flex-col p-10 bg-white rounded-xl border border-gray-100 border-solid shadow-md max-w-[550px] max-md:px-5 "
                        >
                            <div
                                class="text-2xl font-medium text-center text-slate-800 max-md:max-w-full"
                            >
                                Пополнить банковской картой
                            </div>
                            <div
                                class="mt-8 text-xs leading-4 uppercase text-slate-600 max-md:max-w-full"
                            >
                                Укажите сумму
                            </div>

                            <div class="container mx-auto inline-flex justify-start">
                                <div class="relative mb-6 w-40">
                                    <input type="text" v-model="dollar" @change="convertDollarToRouble(dollar)"
                                           id="input-group-1"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 "
                                           :class="{ 'bg-red-200': errors.dollar }"
                                           :placeholder="dollar">

                                    <div
                                        class="absolute inset-y-0 end-2.5 flex items-center ps-3.5 pointer-events-none">
                                        <svg class="stroke-gray-400" width="25px" height="25px" viewBox="0 0 24 24"
                                             fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M18 8.5V8.35417C18 6.50171 16.4983 5 14.6458 5H9.5C7.567 5 6 6.567 6 8.5C6 10.433 7.567 12 9.5 12H14.5C16.433 12 18 13.567 18 15.5C18 17.433 16.433 19 14.5 19H9.42708C7.53436 19 6 17.4656 6 15.5729V15.5M12 3V21"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>

                                    </div>
                                </div>
                                <div class="relative mb-6 w-40">
                                    <input type="text" v-model="rouble" @change="convertRoubleToDollar(rouble)"
                                           id="input-group-1"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 "
                                           :class="{ 'bg-red-200': errors.rouble }"
                                           :placeholder="rouble">

                                    <div
                                        class="absolute inset-y-0 end-2.5 flex items-center ps-3.5 pointer-events-none">
                                        <svg class=" fill-gray-400" width="25px" height="25px" viewBox="0 0 24 24"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M14.5,14H5a1,1,0,0,1,0-2H7V4A.99942.99942,0,0,1,8,3h6.5a5.5,5.5,0,0,1,0,11ZM9,12h5.5a3.5,3.5,0,0,0,0-7H9Z"/>
                                            <path
                                                d="M8,21a.99942.99942,0,0,1-1-1V12a1,1,0,0,1,2,0v8A.99942.99942,0,0,1,8,21Z"/>
                                            <path d="M14,18H5a1,1,0,0,1,0-2h9a1,1,0,0,1,0,2Z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-4 self-start mt-6">
                                <div v-for="card in cards"
                                    class="flex flex-col justify-center my-auto text-xs leading-5 text-white"
                                >
                                    <div
                                        class="flex  flex-col pt-8 pb-2  bg-blue-400 hover:bg-blue-500 rounded-lg w-28 h-20">
                                        <div class="flex gap-1 justify-end mx-2 whitespace-nowrap">
                                            <div>{{card.cardNumber}}</div>
                                        </div>
                                        <div class="flex gap-1 justify-end mx-2">{{ card.month }} / {{ card.year }}</div>
                                    </div>
                                </div>
                                <div
                                    class="group flex-col justify-center my-auto text-xs leading-5 text-gray-600  over:border-gray-300 "
                                >
                                    <div
                                        class="flex  flex-col pt-8 pb-2  bg-gray-200 group-hover:bg-gray-300 rounded-lg w-28 h-20">
                                        <div class="flex gap-1 justify-center mx-2 ">
                                            <div>
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                     stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M12 4.5v15m7.5-7.5h-15"/>
                                                </svg>

                                            </div>
                                        </div>
                                        <div class="flex gap-1 justify-center mx-2 font-bold ">Новая карта</div>
                                    </div>
                                </div>
                            </div>

                            <div class="inline-flex">
                                <div
                                    class="cardbackround bg-cover bg-blue-400 hover:bg-blue-500 rounded-lg mt-6 w-2/3 h-52 max-md:max-w-full overflow-visible z-10"

                                >


                                    <div class="container mx-auto ">
                                        <div class="relative mt-12 w-72 mx-2.5">
                                            <label class=" text-sm text-white">номер карты</label>
                                            <input v-model="cardNumber" type="text"
                                                   :class="{ 'bg-red-200': errors.cardNumber }"
                                                   v-bind="cardNumberAttrs"
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5 "
                                                   placeholder="номер карты">


                                        </div>
                                        <div class="inline-flex mt-8 ">
                                            <div class="relative  w-1/6 mx-2.5">

                                                <input type="text"
                                                       v-model="month"
                                                       v-bind="monthAttrs"
                                                       :class="{ 'bg-red-200': !expirationDate }"
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5 "
                                                       placeholder="mm">


                                            </div>
                                            <div class="relative  w-1/6 mx-2.5">
                                                <input type="text"
                                                       v-model="year"
                                                       v-bind="yearAttrs"
                                                       :class="{ 'bg-red-200': !expirationDate }"
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500  focus:border-blue-500 block w-full p-2.5 "
                                                       placeholder="yy">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="flex justify-end  cardbackroundcvc bg-cover bg-center rounded-lg -mx-2 mt-6 w-60 h-52 max-md:max-w-full  overflow-hidden z-5"
                                >
                                    <div class=" mt-12 w-20 mx-10">
                                        <label class="relative text-gray-500 ">cvv/cvc</label>
                                        <input type="text"
                                               v-bind="cvcAttrs"
                                               v-model="cvc"
                                               :class="{ 'bg-red-200': errors.cvc }"
                                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5 "
                                               placeholder="000">

                                        <InputError :message="props.errors.cvc" class="mt-2"/>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="flex gap-3 items-start mt-6 text-sm leading-5 text-slate-600 max-md:flex-wrap"
                            >


                                <div class="flex flex-auto gap-1.5 max-md:flex-wrap">
                                    <div class="inline-flex items-center">
                                        <label class="relative flex items-center p-3 rounded-full cursor-pointer"
                                        >
                                            <input type="checkbox"
                                                   v-model="remember"
                                                   class="before:content[''] peer relative h-5 w-5 cursor-pointer appearance-none rounded-md border border-blue-gray-200 transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-blue-gray-500 before:opacity-0 before:transition-opacity checked:border-gray-900 checked:bg-gray-900 checked:before:bg-gray-900 hover:before:opacity-10"
                                                   id="check"/>
                                            <span
                                                class="absolute text-white transition-opacity opacity-0 pointer-events-none top-2/4 left-2/4 -translate-y-2/4 -translate-x-2/4 peer-checked:opacity-100">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"
           stroke="currentColor" stroke-width="1">
        <path fill-rule="evenodd"
              d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
              clip-rule="evenodd"></path>
      </svg>
    </span>
                                        </label>
                                        <label class="mt-px  text-gray-700 cursor-pointer select-none">
                                            Запомнить эту карту. Это безопасно.
                                        </label>
                                    </div>

                                    <img
                                        loading="lazy"
                                        src="https://cdn.builder.io/api/v1/image/assets/TEMP/15b53e4e5e05dece705ef61e9808124975eb249abf613db566e6a49bdbc4920c?apiKey=686fd7d72a514caaa122e42a90bcee15&"
                                        class="shrink-0 w-5 aspect-square"
                                     alt=""/>
                                </div>
                            </div>
                            <div class="mt-2 text-sm  text-gray-700 leading-6  max-md:max-w-full">
                                Сохраняя карту, вы соглашаетесь с
                                <span class="text-blue-500">условиями привязки карты.</span>
                            </div>

                            <button
                                class="justify-center self-start px-8  mt-8  select-none font-sans font-bold text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none  text-xs py-3 bg-blue-500  text-white shadow-md shadow-gray-900/10 hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none rounded-full"
                                type="submit">
                                Оплатить
                            </button>


                        </div>
                    </div>
                </div>
            </form>
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
