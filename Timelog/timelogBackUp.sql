--
-- PostgreSQL database dump
--

-- Dumped from database version 17.4
-- Dumped by pg_dump version 17.4

-- Started on 2025-09-13 11:59:32

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 220 (class 1259 OID 16671)
-- Name: leave_requests; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.leave_requests (
    id integer NOT NULL,
    user_id integer,
    start_date date NOT NULL,
    end_date date NOT NULL,
    status character varying(10) DEFAULT 'pending'::character varying
);


ALTER TABLE public.leave_requests OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 16670)
-- Name: leave_requests_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.leave_requests_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.leave_requests_id_seq OWNER TO postgres;

--
-- TOC entry 4877 (class 0 OID 0)
-- Dependencies: 219
-- Name: leave_requests_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.leave_requests_id_seq OWNED BY public.leave_requests.id;


--
-- TOC entry 222 (class 1259 OID 16684)
-- Name: overtime_requests; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.overtime_requests (
    id integer NOT NULL,
    user_id integer,
    overtime_date date NOT NULL,
    hours numeric(4,1) NOT NULL,
    status character varying(10) DEFAULT 'pending'::character varying
);


ALTER TABLE public.overtime_requests OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 16683)
-- Name: overtime_requests_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.overtime_requests_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.overtime_requests_id_seq OWNER TO postgres;

--
-- TOC entry 4878 (class 0 OID 0)
-- Dependencies: 221
-- Name: overtime_requests_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.overtime_requests_id_seq OWNED BY public.overtime_requests.id;


--
-- TOC entry 218 (class 1259 OID 16661)
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id integer NOT NULL,
    username character varying(50) NOT NULL,
    password character varying(255) NOT NULL,
    role character varying(10) NOT NULL,
    CONSTRAINT users_role_check CHECK (((role)::text = ANY ((ARRAY['admin'::character varying, 'employee'::character varying])::text[])))
);


ALTER TABLE public.users OWNER TO postgres;

--
-- TOC entry 217 (class 1259 OID 16660)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO postgres;

--
-- TOC entry 4879 (class 0 OID 0)
-- Dependencies: 217
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- TOC entry 4706 (class 2604 OID 16674)
-- Name: leave_requests id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.leave_requests ALTER COLUMN id SET DEFAULT nextval('public.leave_requests_id_seq'::regclass);


--
-- TOC entry 4708 (class 2604 OID 16687)
-- Name: overtime_requests id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.overtime_requests ALTER COLUMN id SET DEFAULT nextval('public.overtime_requests_id_seq'::regclass);


--
-- TOC entry 4705 (class 2604 OID 16664)
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- TOC entry 4869 (class 0 OID 16671)
-- Dependencies: 220
-- Data for Name: leave_requests; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.leave_requests (id, user_id, start_date, end_date, status) FROM stdin;
\.


--
-- TOC entry 4871 (class 0 OID 16684)
-- Dependencies: 222
-- Data for Name: overtime_requests; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.overtime_requests (id, user_id, overtime_date, hours, status) FROM stdin;
\.


--
-- TOC entry 4867 (class 0 OID 16661)
-- Dependencies: 218
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, username, password, role) FROM stdin;
3	admin2	admin2	admin
2	employee1	$2y$10$VMGetfCSaCXgcjPNls6Up.FNK.jpTYaY7jE1rB6570OQDYypE/FcK	employee
6	reoandohuyan@gmail.com	$2y$10$z47uRsLjt07xLDY9I6QzTuAtk8THQxraVsl07BD6rgoGiWSHyWhfG	employee
7	tyronreid@gmail.com	$2y$10$triFj4xai9zcxd4tEZ3pd.S0Lwf3vvPA86Yqgw6.8FQo4eMlFTT3u	employee
5	admin1	$2y$10$RP5M7yw4BJEunu3PhIq59OBUmQXPWL8LclaBCH5DGBFbkwmZYhsfq	admin
\.


--
-- TOC entry 4880 (class 0 OID 0)
-- Dependencies: 219
-- Name: leave_requests_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.leave_requests_id_seq', 29, true);


--
-- TOC entry 4881 (class 0 OID 0)
-- Dependencies: 221
-- Name: overtime_requests_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.overtime_requests_id_seq', 15, true);


--
-- TOC entry 4882 (class 0 OID 0)
-- Dependencies: 217
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 7, true);


--
-- TOC entry 4716 (class 2606 OID 16677)
-- Name: leave_requests leave_requests_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.leave_requests
    ADD CONSTRAINT leave_requests_pkey PRIMARY KEY (id);


--
-- TOC entry 4718 (class 2606 OID 16690)
-- Name: overtime_requests overtime_requests_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.overtime_requests
    ADD CONSTRAINT overtime_requests_pkey PRIMARY KEY (id);


--
-- TOC entry 4712 (class 2606 OID 16667)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 4714 (class 2606 OID 16669)
-- Name: users users_username_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_username_key UNIQUE (username);


--
-- TOC entry 4719 (class 2606 OID 16678)
-- Name: leave_requests leave_requests_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.leave_requests
    ADD CONSTRAINT leave_requests_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- TOC entry 4720 (class 2606 OID 16691)
-- Name: overtime_requests overtime_requests_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.overtime_requests
    ADD CONSTRAINT overtime_requests_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(id);


-- Completed on 2025-09-13 11:59:32

--
-- PostgreSQL database dump complete
--

