import { useState } from "react";
import BalanceShow from "../components/BalanceShow";
import { getWalletBalance, getWalletTransactions } from "../api/wallet";

const Balance = () => {


	const [form, setForm] = useState({
		document: "",
		celPhone: ""
	});
	const [message, setMessage] = useState("");
	const [errorStyle, setErrorStyle] = useState("");
	const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
		event.preventDefault();
		setForm({
			...form,
			[event.target.name]: event.target.value
		});
	};


	const [balance, setBalance] = useState(0);
	const [transactions, setTransactions] = useState([]);

	const handleSubmit = async (event: React.FormEvent<HTMLFormElement>) => {
		event.preventDefault();
		try {
			const response = await getWalletBalance({
				document: form.document,
				celPhone: form.celPhone
			});
			if (!response.success) {
				setMessage(response.message);
				setErrorStyle("bg-red-100 text-red-800 border-red-400");
				setTimeout(() => {
					setMessage("");
				}, 2000);
				return false;
			}
			getTransactions();
			const balanceValue =
    			response.data && typeof response.data.balance === "number"
        		? response.data.balance
        		: 0;
			setMessage(response.message);
			setBalance(balanceValue);
			setErrorStyle("bg-green-100 text-green-800 border-green-400");
			setForm({
				document: "",
				celPhone: ""
			});
			setTimeout(() => {
				setMessage("");
			}, 2000);
		} catch (error) {
			setMessage(error.message);
			setErrorStyle("bg-red-100 text-red-800 border-red-400");
			setTimeout(() => {
				setMessage("");
				setForm({
					document: "",
					celPhone: ""
				});
			}, 3000);
		}
	};

	const getTransactions = async () => {
		try {
			const response = await getWalletTransactions({
				document: form.document,
				celPhone: form.celPhone
			});

			if (!response.success  || !response.data) {
				setTransactions([]);
				return false;
			}

			setTransactions(response.data.transactions || []);



			
		} catch (error) {
			console.error("Error fetching transactions:", error);
			setTransactions([]);
		}
	}

	return (
		<>
		<div className="max-w-xl mx-auto p-6 bg-white shadow-md rounded-lg mt-4">
			<h2 className="text-2xl text-center font-bold mb-4">Saldo Disponible</h2>

			<h2 className="text-4xl font-bold mb-4 text-center">${balance}</h2>

			{message && <p className={` ${errorStyle}  h-auto  mt-4 rounded-lg p-2 mb-4 text-center` } >{message}</p>}
			<form className="space-y-4" onSubmit={handleSubmit}>
				<div className="mb-4">
					<label className="block text-sm font-medium text-gray-700">Documento</label>
					<input 
						type="text"
						name="document"
						value={form.document}
						onChange={handleChange}
						className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:content-none" />
				</div>
				<div className="mb-4">
					<label className="block text-sm font-medium text-gray-700">Teléfono</label>
					<input 
						type="tel" 
						name="celPhone"
						value={form.celPhone}
						onChange={handleChange}
						className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:content-none" />
				</div>
				
				
				<button
				type="submit"
				className="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition duration-200"
				>
					Cosultar Saldo
				</button>
			</form>


			
			<BalanceShow transactions={transactions} />

		</div>
		
		</>
  );
}

export default Balance;