import { Transaction } from './wallet';

export interface ClientRequest {
	document: string;
	firstName: string;
	lastName: string;
	email: string;
	celPhone: string;
  }
  
  export interface WalletAddRequest {
	document: string;
	celPhone: string;
	amount: number;
  }
  
  export interface PayRequest {
	document: string;
	celPhone: string;
	amount: number;
	description: string;
  }
  
  export interface ConfirmPaymentRequest {
	sessionId: string;
	token: string;
  }

  export interface GetWalletBalanceRequest {
	document: string;
	celPhone: string;
  }
  


  export interface ApiResponse<T = unknown> {
	success: boolean;
	message: string;
	data?: T;
  }
  

export interface Client{
	document: string;
	firstName: string;
	lastName: string;
	email: string;
	phone: string;
	
}


export interface Transaction {
	
	amount: number;
	Description: string;
	status: string; 
	date: string; 
}



export interface ClientRegisterResponse {
	message: string;
	data?: Client;
}

export interface WalletAddResponse {
	message: string;
	data?: {
		balance: string;
	};
}



export interface WalletHistoryResponse {
	success: boolean;
	message: string;
	data?: {
		transactions : Transaction[];
	};
}

export interface PayResponse{
	success: boolean;
	message: string;
	data?: {
		sessionId: string;
		token: string;
	};
}

export interface ConfirmPaymentResponse {
	success: boolean;
	message: string;
	data?: {
		wallet_balance: string;
	};
}

export interface GetWalletBalanceResponse {
	success: boolean;
	message: string;
	data?: {
		balance: string;
	};
}

