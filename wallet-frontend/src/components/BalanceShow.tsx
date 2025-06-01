

const BalanceShow = ({transactions}) => {

	return (
		<div className=" mx-auto p-6 bg-white  rounded-lg mt-10 ">
			
			
			
			<h3 className="text-xl text-center font-semibold mb-2">Historial de transacciones</h3>
			<div className="overflow-x-auto">

				{transactions.length <= 0 && (
					<p className="text-center">No hay transacciones</p>
				)}

				{transactions.length > 0 && (<>
					<p className="text-gray-700 mb-4">
						Total de transacciones: {transactions.length}
					</p>
					<table className="min-w-full bg-white border border-gray-200">
						<thead>
							<tr>
							<th className="px-4 py-2 border-b">Fecha</th>
							<th className="px-4 py-2 border-b">Estado</th>
							<th className="px-4 py-2 border-b">Monto</th>
							</tr>
						</thead>
						<tbody>
							{transactions.length > 0 ? (
							transactions.map((tx) => (
								<tr key={tx.id} className="text-center">
								<td className="px-4 py-2 border-b">{tx.created_at}</td>
								<td className="px-4 py-2 border-b">{tx.status}</td>
								<td className="px-4 py-2 border-b">${Math.abs(tx.amount).toLocaleString()}</td>
								</tr>
							))
							) : (
							<tr>
								<td colSpan={3} className="text-center py-4 text-gray-500">
								No hay transacciones registradas.
								</td>
							</tr>
							)}
						</tbody>
					</table>
					</>)
				}
			</div>


		</div>
	);
}

export default BalanceShow;