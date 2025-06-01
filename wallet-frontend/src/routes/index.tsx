import { BrowserRouter, Routes, Route } from "react-router-dom";
import Register from "./../pages/Register";
import Recharge from "./../pages/Recharge";
import Pay from "./../pages/Pay";
import ConfirmPayment from "./../pages/ConfirmPayment";
import Balance from "./../pages/Balance";
import MainLayout from "./../layouts/MainLayout";
import Home from "./../pages/Home";


export default function AppRoutes() {
  return (
	<BrowserRouter>
	  <MainLayout>
		<Routes>
		  <Route path="/" element={<Home />} />
		  <Route path="/register" element={<Register />} />
		  <Route path="/recharge" element={<Recharge />} />
		  <Route path="/pay" element={<Pay />} />
		  <Route path="/confirm/:sessionId" element={<ConfirmPayment />} />
		  <Route path="/balance" element={<Balance />} />
		</Routes>
	  </MainLayout>
	</BrowserRouter>
  );
}