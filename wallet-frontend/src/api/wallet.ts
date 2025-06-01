import axios from "axios";


import type { 
	ClientRequest,
	WalletAddRequest,
	PayRequest,
	ConfirmPaymentRequest,
	ApiResponse,
	GetWalletBalanceRequest,
	GetWalletBalanceResponse,
	WalletHistoryResponse,
	ClientRegisterResponse,
	WalletAddResponse,
	PayResponse,
	ConfirmPaymentResponse

 } from "../types/wallet";


const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || "http://localhost:8010/";


const api = axios.create({
	baseURL: API_BASE_URL,
	headers: {
		"Content-Type": "application/json",
		"Accept": "application/json",
	},
});

api.interceptors.response.use(
	response=>response,
	error=>{
		if(error.response) {
			throw error.response.data;
		}
		throw error
	}
);

/// creo funcion generica de post para la llamade de api para retornar la promesa
async function apiCall<T, R= ApiResponse>(url: string, data: T): Promise<R> {
	try {
		const response = await api.post<R>(`${url}`, data);
		return response.data;
	} catch (error) {
		if (axios.isAxiosError(error)) {
			throw new Error(error.response?.data.message || "API call failed");
		}
		throw new Error("An unexpected error occurred");
	}
}


export async function createClient(data: ClientRequest): Promise<ClientRegisterResponse> {
	return apiCall<ClientRequest, ClientRegisterResponse>(`/register-client`, data);
}

export async function rechargeWallet(data: WalletAddRequest): Promise<WalletAddResponse> {
	return apiCall<WalletAddRequest, WalletAddResponse>(`/load-wallet`, data);
}

export async function payWallet(data: PayRequest): Promise<PayResponse> {
	return apiCall<PayRequest,PayResponse>(`/pay-with-wallet`, data);
}

export async function confirmPayment(data: ConfirmPaymentRequest): Promise<ConfirmPaymentResponse> {
	return apiCall<ConfirmPaymentRequest,ConfirmPaymentResponse>(`/confirm-payment`, data);
}

export async function getWalletBalance(data:GetWalletBalanceRequest): Promise<GetWalletBalanceResponse> {
	return apiCall<GetWalletBalanceRequest,GetWalletBalanceResponse>(`/wallet-balance`, data);
}

export async function getWalletTransactions(data: GetWalletBalanceRequest): Promise<WalletHistoryResponse> {
	return apiCall<GetWalletBalanceRequest, WalletHistoryResponse>(`/get-history`, data);
}