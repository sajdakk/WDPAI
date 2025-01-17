PGDMP  *        
             |            db    16.1 (Debian 16.1-1.pgdg120+1)    16.1 D    {           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                      false            |           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                      false            }           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                      false            ~           1262    16384    db    DATABASE     m   CREATE DATABASE db WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'en_US.utf8';
    DROP DATABASE db;
                admin    false            �            1255    16521 �   add_book(character varying, integer, integer, date, integer, character varying, character varying, text, date, integer, character varying[], date, date)    FUNCTION     �  CREATE FUNCTION public.add_book(p_title character varying, p_genre_id integer, p_language_id integer, p_date_of_publication date, p_page_count integer, p_image character varying, p_isbn_number character varying, p_description text, p_upload_date date, p_created_by integer, p_authors character varying[], p_accept_date date DEFAULT NULL::date, p_reject_date date DEFAULT NULL::date) RETURNS integer
    LANGUAGE plpgsql
    AS $$DECLARE
    v_author_id INT;
    v_book_id INT;
    v_author_name VARCHAR(255);
    v_author_surname VARCHAR(255);
    v_author_fullname VARCHAR(255);
BEGIN
    -- Start a transaction
    BEGIN
        -- Iterate through the array of author names
        FOREACH v_author_fullname IN ARRAY p_authors
        LOOP
            -- Extract author_name and author_surname
            SELECT
		substring(v_author_fullname FROM 1 FOR position(' ' IN v_author_fullname) - 1) AS author_name,
		substring(v_author_fullname FROM position(' ' IN v_author_fullname) + 1) AS author_surname
            INTO
                v_author_name,
                v_author_surname;

            -- Check if the author already exists
            SELECT id INTO v_author_id
            FROM authors
            WHERE name = v_author_name AND surname = v_author_surname;

            -- If the author doesn't exist, insert a new author
            IF v_author_id IS NULL THEN
                INSERT INTO authors (name, surname)
                VALUES (v_author_name, v_author_surname)
                RETURNING id INTO v_author_id;
            END IF;
        END LOOP;

        -- Insert the book
        INSERT INTO books (title, genre_id, language_id, date_of_publication, page_count, image, isbn_number, description, upload_date, accept_date, created_by, reject_date)
        VALUES (p_title, p_genre_id, p_language_id, p_date_of_publication, p_page_count, p_image, p_isbn_number, p_description, p_upload_date, p_accept_date, p_created_by, p_reject_date)
        RETURNING id INTO v_book_id;

        -- Link authors to the book in the author_book table
        FOREACH v_author_fullname IN ARRAY p_authors
        LOOP
            -- Extract author_name and author_surname
            SELECT
               substring(v_author_fullname FROM 1 FOR position(' ' IN v_author_fullname) - 1) AS author_name,
		substring(v_author_fullname FROM position(' ' IN v_author_fullname) + 1) AS author_surname
              INTO
                v_author_name,
                v_author_surname;

            -- Get the author_id for the current author
            SELECT id INTO v_author_id
            FROM authors
            WHERE name = v_author_name AND surname = v_author_surname;

            -- Insert the relationship in the author_book table
            INSERT INTO author_book (book_id, author_id)
            VALUES (v_book_id, v_author_id);
        END LOOP;

    EXCEPTION
        WHEN OTHERS THEN
            -- Handle exceptions and optionally roll back the transaction
            -- ROLLBACK; -- Uncomment if you want to roll back on exception
            -- Optionally, re-raise the exception
            RAISE EXCEPTION 'Error in add_book function: %', SQLERRM;
    END;

    -- Return the book_id
    RETURN v_book_id;
END;
$$;
 X  DROP FUNCTION public.add_book(p_title character varying, p_genre_id integer, p_language_id integer, p_date_of_publication date, p_page_count integer, p_image character varying, p_isbn_number character varying, p_description text, p_upload_date date, p_created_by integer, p_authors character varying[], p_accept_date date, p_reject_date date);
       public          admin    false            �            1255    16553     remove_user_and_reviews(integer)    FUNCTION     �  CREATE FUNCTION public.remove_user_and_reviews(user_id_to_remove integer) RETURNS void
    LANGUAGE plpgsql
    AS $$
DECLARE
    -- Declare variables if needed
BEGIN
    -- Start the transaction
    BEGIN
        -- Delete associated reviews
        DELETE FROM reviews WHERE user_id = user_id_to_remove;
		
		 -- Delete records from author_book associated with the books created by the user
        DELETE FROM author_book WHERE book_id IN (SELECT id FROM books WHERE created_by = user_id_to_remove);
		
		 -- Delete records from favorites associated with the books created by the user
    DELETE FROM favorites WHERE book_id IN (SELECT id FROM books WHERE created_by = user_id_to_remove);

		 -- Delete books created by the user
        DELETE FROM books WHERE created_by = user_id_to_remove;
		

        -- Delete the user
        DELETE FROM users WHERE id = user_id_to_remove;
		
		

    EXCEPTION
        WHEN OTHERS THEN
            -- Propagate the error
            RAISE;
    END;
END;
$$;
 I   DROP FUNCTION public.remove_user_and_reviews(user_id_to_remove integer);
       public          admin    false            �            1259    16427    author_book    TABLE     i   CREATE TABLE public.author_book (
    id integer NOT NULL,
    author_id integer,
    book_id integer
);
    DROP TABLE public.author_book;
       public         heap    admin    false            �            1259    16426    author_book_id_seq    SEQUENCE     �   ALTER TABLE public.author_book ALTER COLUMN id ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.author_book_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);
            public          admin    false    224            �            1259    16419    authors    TABLE     Z   CREATE TABLE public.authors (
    id integer NOT NULL,
    name text,
    surname text
);
    DROP TABLE public.authors;
       public         heap    admin    false            �            1259    16418    authors_id_seq    SEQUENCE     �   ALTER TABLE public.authors ALTER COLUMN id ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.authors_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);
            public          admin    false    222            �            1259    16389    books    TABLE     �  CREATE TABLE public.books (
    id integer NOT NULL,
    title text NOT NULL,
    genre_id integer NOT NULL,
    language_id integer NOT NULL,
    date_of_publication timestamp without time zone NOT NULL,
    page_count integer NOT NULL,
    image text NOT NULL,
    isbn_number text NOT NULL,
    description text NOT NULL,
    upload_date timestamp without time zone NOT NULL,
    accept_date timestamp without time zone,
    created_by integer NOT NULL,
    reject_date timestamp without time zone
);
    DROP TABLE public.books;
       public         heap    admin    false            �            1259    16388    books_id_seq    SEQUENCE     �   ALTER TABLE public.books ALTER COLUMN id ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.books_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);
            public          admin    false    216            �            1259    16433 	   favorites    TABLE     e   CREATE TABLE public.favorites (
    id integer NOT NULL,
    book_id integer,
    user_id integer
);
    DROP TABLE public.favorites;
       public         heap    admin    false            �            1259    16432    favourites_id_seq    SEQUENCE     �   ALTER TABLE public.favorites ALTER COLUMN id ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.favourites_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);
            public          admin    false    226            �            1259    16399    genres    TABLE     H   CREATE TABLE public.genres (
    id integer NOT NULL,
    title text
);
    DROP TABLE public.genres;
       public         heap    admin    false            �            1259    16398    genres_id_seq    SEQUENCE     �   ALTER TABLE public.genres ALTER COLUMN id ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.genres_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);
            public          admin    false    218            �            1259    16409 	   languages    TABLE     N   CREATE TABLE public.languages (
    id integer NOT NULL,
    language text
);
    DROP TABLE public.languages;
       public         heap    admin    false            �            1259    16408    languages_id_seq    SEQUENCE     �   ALTER TABLE public.languages ALTER COLUMN id ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.languages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);
            public          admin    false    220            �            1259    16439    reviews    TABLE     2  CREATE TABLE public.reviews (
    id integer NOT NULL,
    book_id integer NOT NULL,
    user_id integer NOT NULL,
    content text NOT NULL,
    rate integer NOT NULL,
    upload_date timestamp without time zone,
    accept_date timestamp without time zone,
    reject_date timestamp without time zone
);
    DROP TABLE public.reviews;
       public         heap    admin    false            �            1259    16438    reviews_id_seq    SEQUENCE     �   ALTER TABLE public.reviews ALTER COLUMN id ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.reviews_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);
            public          admin    false    228            �            1259    16537    roles    TABLE     F   CREATE TABLE public.roles (
    id integer NOT NULL,
    name text
);
    DROP TABLE public.roles;
       public         heap    admin    false            �            1259    16536    roles_id_seq    SEQUENCE     �   ALTER TABLE public.roles ALTER COLUMN id ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);
            public          admin    false    232            �            1259    16555    top_books_view    VIEW     |  CREATE VIEW public.top_books_view AS
SELECT
    NULL::integer AS id,
    NULL::text AS title,
    NULL::text AS author_string,
    NULL::numeric AS average_mark,
    NULL::bigint AS rate_count,
    NULL::integer AS genre_id,
    NULL::integer AS language_id,
    NULL::timestamp without time zone AS date_of_publication,
    NULL::integer AS page_count,
    NULL::text AS image,
    NULL::text AS isbn_number,
    NULL::text AS description,
    NULL::timestamp without time zone AS upload_date,
    NULL::timestamp without time zone AS accept_date,
    NULL::integer AS created_by,
    NULL::timestamp without time zone AS reject_date;
 !   DROP VIEW public.top_books_view;
       public          admin    false            �            1259    16447    users    TABLE     �   CREATE TABLE public.users (
    id integer NOT NULL,
    email text NOT NULL,
    password text NOT NULL,
    name character varying NOT NULL,
    surname character varying NOT NULL,
    avatar text,
    role_id integer NOT NULL
);
    DROP TABLE public.users;
       public         heap    admin    false            �            1259    16446    users_id_seq    SEQUENCE     �   ALTER TABLE public.users ALTER COLUMN id ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);
            public          admin    false    230            p          0    16427    author_book 
   TABLE DATA                 public          admin    false    224   Ae       n          0    16419    authors 
   TABLE DATA                 public          admin    false    222   �e       h          0    16389    books 
   TABLE DATA                 public          admin    false    216   �f       r          0    16433 	   favorites 
   TABLE DATA                 public          admin    false    226   �n       j          0    16399    genres 
   TABLE DATA                 public          admin    false    218   Ao       l          0    16409 	   languages 
   TABLE DATA                 public          admin    false    220   (p       t          0    16439    reviews 
   TABLE DATA                 public          admin    false    228   �p       x          0    16537    roles 
   TABLE DATA                 public          admin    false    232   �y       v          0    16447    users 
   TABLE DATA                 public          admin    false    230   ^z                  0    0    author_book_id_seq    SEQUENCE SET     A   SELECT pg_catalog.setval('public.author_book_id_seq', 22, true);
          public          admin    false    223            �           0    0    authors_id_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('public.authors_id_seq', 24, true);
          public          admin    false    221            �           0    0    books_id_seq    SEQUENCE SET     ;   SELECT pg_catalog.setval('public.books_id_seq', 18, true);
          public          admin    false    215            �           0    0    favourites_id_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.favourites_id_seq', 97, true);
          public          admin    false    225            �           0    0    genres_id_seq    SEQUENCE SET     <   SELECT pg_catalog.setval('public.genres_id_seq', 15, true);
          public          admin    false    217            �           0    0    languages_id_seq    SEQUENCE SET     ?   SELECT pg_catalog.setval('public.languages_id_seq', 14, true);
          public          admin    false    219            �           0    0    reviews_id_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('public.reviews_id_seq', 27, true);
          public          admin    false    227            �           0    0    roles_id_seq    SEQUENCE SET     :   SELECT pg_catalog.setval('public.roles_id_seq', 3, true);
          public          admin    false    231            �           0    0    users_id_seq    SEQUENCE SET     ;   SELECT pg_catalog.setval('public.users_id_seq', 15, true);
          public          admin    false    229            �           2606    16431    author_book author_book_pkey 
   CONSTRAINT     Z   ALTER TABLE ONLY public.author_book
    ADD CONSTRAINT author_book_pkey PRIMARY KEY (id);
 F   ALTER TABLE ONLY public.author_book DROP CONSTRAINT author_book_pkey;
       public            admin    false    224            �           2606    16425    authors authors_pkey 
   CONSTRAINT     R   ALTER TABLE ONLY public.authors
    ADD CONSTRAINT authors_pkey PRIMARY KEY (id);
 >   ALTER TABLE ONLY public.authors DROP CONSTRAINT authors_pkey;
       public            admin    false    222            �           2606    16397    books books_ISBN_number_key 
   CONSTRAINT     _   ALTER TABLE ONLY public.books
    ADD CONSTRAINT "books_ISBN_number_key" UNIQUE (isbn_number);
 G   ALTER TABLE ONLY public.books DROP CONSTRAINT "books_ISBN_number_key";
       public            admin    false    216            �           2606    16395    books books_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.books
    ADD CONSTRAINT books_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.books DROP CONSTRAINT books_pkey;
       public            admin    false    216            �           2606    16437    favorites favourites_pkey 
   CONSTRAINT     W   ALTER TABLE ONLY public.favorites
    ADD CONSTRAINT favourites_pkey PRIMARY KEY (id);
 C   ALTER TABLE ONLY public.favorites DROP CONSTRAINT favourites_pkey;
       public            admin    false    226            �           2606    16405    genres genres_pkey 
   CONSTRAINT     P   ALTER TABLE ONLY public.genres
    ADD CONSTRAINT genres_pkey PRIMARY KEY (id);
 <   ALTER TABLE ONLY public.genres DROP CONSTRAINT genres_pkey;
       public            admin    false    218            �           2606    16407    genres genres_title_key 
   CONSTRAINT     S   ALTER TABLE ONLY public.genres
    ADD CONSTRAINT genres_title_key UNIQUE (title);
 A   ALTER TABLE ONLY public.genres DROP CONSTRAINT genres_title_key;
       public            admin    false    218            �           2606    16417     languages languages_language_key 
   CONSTRAINT     _   ALTER TABLE ONLY public.languages
    ADD CONSTRAINT languages_language_key UNIQUE (language);
 J   ALTER TABLE ONLY public.languages DROP CONSTRAINT languages_language_key;
       public            admin    false    220            �           2606    16415    languages languages_pkey 
   CONSTRAINT     V   ALTER TABLE ONLY public.languages
    ADD CONSTRAINT languages_pkey PRIMARY KEY (id);
 B   ALTER TABLE ONLY public.languages DROP CONSTRAINT languages_pkey;
       public            admin    false    220            �           2606    16445    reviews reviews_pkey 
   CONSTRAINT     R   ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT reviews_pkey PRIMARY KEY (id);
 >   ALTER TABLE ONLY public.reviews DROP CONSTRAINT reviews_pkey;
       public            admin    false    228            �           2606    16545    roles roles_name_key 
   CONSTRAINT     O   ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_name_key UNIQUE (name);
 >   ALTER TABLE ONLY public.roles DROP CONSTRAINT roles_name_key;
       public            admin    false    232            �           2606    16543    roles roles_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.roles DROP CONSTRAINT roles_pkey;
       public            admin    false    232            �           2606    16455    users users_email_key 
   CONSTRAINT     Q   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_key UNIQUE (email);
 ?   ALTER TABLE ONLY public.users DROP CONSTRAINT users_email_key;
       public            admin    false    230            �           2606    16453    users users_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
       public            admin    false    230            f           2618    16558    top_books_view _RETURN    RULE        CREATE OR REPLACE VIEW public.top_books_view AS
 SELECT b.id,
    b.title,
    COALESCE(a.author_string, ''::text) AS author_string,
    COALESCE(avg(r.rate), (0)::numeric) AS average_mark,
    count(r.rate) AS rate_count,
    b.genre_id,
    b.language_id,
    b.date_of_publication,
    b.page_count,
    b.image,
    b.isbn_number,
    b.description,
    b.upload_date,
    b.accept_date,
    b.created_by,
    b.reject_date
   FROM ((public.books b
     LEFT JOIN ( SELECT ba.book_id,
            string_agg(((author.name || ' '::text) || author.surname), ', '::text) AS author_string
           FROM (public.authors author
             JOIN public.author_book ba ON ((author.id = ba.author_id)))
          GROUP BY ba.book_id) a ON ((b.id = a.book_id)))
     LEFT JOIN ( SELECT reviews.book_id,
            reviews.rate
           FROM public.reviews
          WHERE (reviews.accept_date IS NOT NULL)) r ON ((b.id = r.book_id)))
  WHERE (b.accept_date IS NOT NULL)
  GROUP BY b.id, a.author_string
  ORDER BY COALESCE(avg(r.rate), (0)::numeric) DESC;
 �  CREATE OR REPLACE VIEW public.top_books_view AS
SELECT
    NULL::integer AS id,
    NULL::text AS title,
    NULL::text AS author_string,
    NULL::numeric AS average_mark,
    NULL::bigint AS rate_count,
    NULL::integer AS genre_id,
    NULL::integer AS language_id,
    NULL::timestamp without time zone AS date_of_publication,
    NULL::integer AS page_count,
    NULL::text AS image,
    NULL::text AS isbn_number,
    NULL::text AS description,
    NULL::timestamp without time zone AS upload_date,
    NULL::timestamp without time zone AS accept_date,
    NULL::integer AS created_by,
    NULL::timestamp without time zone AS reject_date;
       public          admin    false    228    216    216    216    216    216    216    228    216    222    224    224    228    216    216    216    216    216    216    3252    222    222    233            �           2606    16484 &   author_book author_book_author_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.author_book
    ADD CONSTRAINT author_book_author_id_fkey FOREIGN KEY (author_id) REFERENCES public.authors(id);
 P   ALTER TABLE ONLY public.author_book DROP CONSTRAINT author_book_author_id_fkey;
       public          admin    false    3262    222    224            �           2606    16479 $   author_book author_book_book_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.author_book
    ADD CONSTRAINT author_book_book_id_fkey FOREIGN KEY (book_id) REFERENCES public.books(id);
 N   ALTER TABLE ONLY public.author_book DROP CONSTRAINT author_book_book_id_fkey;
       public          admin    false    216    224    3252            �           2606    16474    books books_created_by_fkey    FK CONSTRAINT     }   ALTER TABLE ONLY public.books
    ADD CONSTRAINT books_created_by_fkey FOREIGN KEY (created_by) REFERENCES public.users(id);
 E   ALTER TABLE ONLY public.books DROP CONSTRAINT books_created_by_fkey;
       public          admin    false    216    3272    230            �           2606    16464    books books_genre_id_fkey    FK CONSTRAINT     z   ALTER TABLE ONLY public.books
    ADD CONSTRAINT books_genre_id_fkey FOREIGN KEY (genre_id) REFERENCES public.genres(id);
 C   ALTER TABLE ONLY public.books DROP CONSTRAINT books_genre_id_fkey;
       public          admin    false    216    218    3254            �           2606    16469    books books_language_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.books
    ADD CONSTRAINT books_language_id_fkey FOREIGN KEY (language_id) REFERENCES public.languages(id);
 F   ALTER TABLE ONLY public.books DROP CONSTRAINT books_language_id_fkey;
       public          admin    false    216    3260    220            �           2606    16489 !   favorites favourites_book_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.favorites
    ADD CONSTRAINT favourites_book_id_fkey FOREIGN KEY (book_id) REFERENCES public.books(id);
 K   ALTER TABLE ONLY public.favorites DROP CONSTRAINT favourites_book_id_fkey;
       public          admin    false    216    3252    226            �           2606    16494 !   favorites favourites_user_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.favorites
    ADD CONSTRAINT favourites_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(id);
 K   ALTER TABLE ONLY public.favorites DROP CONSTRAINT favourites_user_id_fkey;
       public          admin    false    230    226    3272            �           2606    16504    reviews reviews_book_id_fkey    FK CONSTRAINT     {   ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT reviews_book_id_fkey FOREIGN KEY (book_id) REFERENCES public.books(id);
 F   ALTER TABLE ONLY public.reviews DROP CONSTRAINT reviews_book_id_fkey;
       public          admin    false    228    216    3252            �           2606    16499    reviews reviews_user_id_fkey    FK CONSTRAINT     {   ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT reviews_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(id);
 F   ALTER TABLE ONLY public.reviews DROP CONSTRAINT reviews_user_id_fkey;
       public          admin    false    230    228    3272            �           2606    16547    users role_id_fkey    FK CONSTRAINT     {   ALTER TABLE ONLY public.users
    ADD CONSTRAINT role_id_fkey FOREIGN KEY (role_id) REFERENCES public.roles(id) NOT VALID;
 <   ALTER TABLE ONLY public.users DROP CONSTRAINT role_id_fkey;
       public          admin    false    230    3276    232            p   ~   x���v
Q���W((M��L�K,-��/�O���Vs�	uV�04�Q0�Q04Ҵ��$RP��P�1	z̀z�I�c�� �fhB��5�$걄zǔx=F������$�:�A�@=\\ -��      n   �   x���MN�0�}N�]@B�?VZ
5��IF��<�,���z/<� ���f���e7[�U�\��m�0�W:������m֩��L�����L��MyzS�ˋt��h?j�w�C���2�GmAhا�y�J���>c?!D�w��Z���w�Þ�N32���jQ�]Q4�Y�Bk;��s��\/5ݒ>�u���bi������F�������H_~O��"�W?�)��      h     x��W�n�F��+j�	�M���fwVN�8�5�`9Q�͠�,���"� �Zy���d#���.���?�/�s��	� Y�ɺ��{ι�N^�?����z�-+�e*Ck�TR�~����˙��љ�Uq4b����k���qǎ�l{a~�u���QAk��+�:���|:�]{�'������6�2�TW���X�Z)6�C�bQ�,�\��c�*��6�Se����*��i�Z��e&C�XR�Pv�(u��Z�r�Ny�oF�/;�W*ʥ`����3�׈��(y��^�2ME+C<j�>m�r��X)��M���{���∌��z����b��fj��e�B��J���TF�׿X�iƜ�j%g*)E�S^3g������?3����[v�kXIQ����]��e*
e���
��@NL�IL�2�R�L�`ת�)f�K�+&G���\f<����������U��&�a��A3b5O6���ZN5靝�^H�I��)���\�}�2��Qڕ������"r5�y�{��B����6<Q������-��0�iٷU�R��yvQ�����:2hu'c�`�z��|�xwf��ĵ	�@���NO?�����2�%��� �`�oi�z�&����mh��q���c)~����O��y���� #x��)��`��\G���b  84�T��!Q'�g����a�����U��	�	�����6�����}#L���JY�	1T!b�_�"��Z�F�=���\:��y�U���\��Wuӣd�#hGiF@Q�D-�S��G�|�^�II��,��k�_8��v-{b�|�����ߪ�C%$��g���b:w�`Os�(���v,��l��p]'����"8z(�P��|�<���D)��@�����.������������J9w�;����l�"|z���^���IK��e8���b)�N$�P��%<|n@��7�*72�:,^��.��؀5���"B��"u�D%��h6;��k�	�vf;X8����;}$$&p��ȐUo�B�9��N	ڬ9�cU�<�@�wP��d���AaF/X�?A/��]X�U)�x��<�jZ�2ĩF����֕�H��QZ�z���D��nV�y�B�0}�e^��F�W�_?�ߡ��x�6�B��뾹U�nT"$(�$\��	�i�z�@���{��R�h#ԭ��q����Y�QŞY��;�G��{�ӄj=�`��{wB�����|��|�'߾W�	m�GlX���u���2cʖ�_�TT��8;+���':>��+P\<�߱��/UO^���韋��V�w�6�����0wN@2I��IC�<�����!�%Bb���)��R#i����k�/ ��Xh�L4u(F�D�@7��w}CM)�q�x�w�Hv��8�E&mW5-4��-� ��92e��.I�:��El����M�6Fr�OH�J�Pa�`�h�]C�<Y�<��1��\(J?�bl�
`y@�Τ����R������&2RǷSQ<������?��C-�\�t����C>��0��b)t`�^R�8�a�P����p`, �SRL��f �ަ<�l`P������o,�	��*��$��O���e��t6{$?�p�R�PÜ:��w�Z�3'�&����]��{�קĨ�,"�Boi�*1Q�uG��xS+:�O�p�0�3ߎQ�8
��]�@Ŗ�����/��� X���R���
�#{4ɰ��ΐ�D�W�U�4 	�� �c�'�TĦN=�B�z��`;��*������E?�2���
�	ժ�9,�{l�0��쪮�jq|�1U��R����W��(�Ȅ��<8����.�$��Q��Z�`�ϝG����eIWH��&�$�ww]}���=#�z0��L��ݚ�]�����P����Jb���gஊE;�Y�s����̀t�x�������i�W��Y?y�n*��!�$i@Iȥ*��׶xߝ{!��3�&��w8\�f�wi*�7�YS��Լ�oj��ԓ��X��)�S�'O�J9ʞ      r   X   x���v
Q���W((M��L�KK,�/�,I-Vs�	uVа4�Q04�Q0״��$J�P�		�-�LI�`b�`L��@� �� ^Fw      j   �   x����n�0�{�"7@��u�¨�41��ik����i����]8b��Sl���./Uv(��e���[p�:'_�]����W�JM���be7�3�T;��_xa�G"$Y�+חkA(�,6[җN���d�X��ޙ��kޚ��k��GO�'��{-��o��F�A<i&p~$�qȼ$=��zִ�;�����O�o&�� ��      l   �   x���M�0�=��M���W,P�� �l,����N�h����<��� ,+n����T�j,4ܐ2�>Ӝ����N
������9��bhR��,ќ�z	o�B�h�D�Mֿ�z���{���5F��6[4�U���#�m@
��9�'�u;�t��$�!�d��[Ba�'d	�M�|P��[E�V��t      t   �  x��K�۸����i�*I%R�iO�]��*��{}ɖ���V�~��g"'�ٗyP`��?��>�����ӗ�.VZ�3GGE'/��������o�Q��_��V���V4V['�
B�&���S(D'd�:�ke���
3�I�"���M=;�{%��A�m<�0�SFU��Qu��3�KT^I�9�`�F�d�b2�LU�4�'$�Q^����;�ޓ�l�kx��u�+�N�Jc-�`��é��r�W�<������J�Z齜Yױ���/xD8�Z�m�jp��0�PmG�Q6�V���Lv䲣"��TM���A���Z%<5B�R�o�:�3���᱌u@����ulg�:�v��<���?k�c�@esx{IHWG�ed�N���V8{�U��*���q�Ldr�<z�؉Fv�����1Q1Tg�Rh�������$0�፨&� ��Q&�|Р��JK�[_/���f�vN��ʍ��d��>�ñc�H&%�'2I�p ��ٱ�D�〸�ZY�-"��%~ X����aU
c=<��g����Ł!I�p��Tu�����V��1������Ԝ:Q�i�M�zԵm[�X��ځ㟊C�2��	������a4���%��s*#BY�}�\7lȉ����FT)�,�$ |$���;(
/]U�fm�_�Ŀ�k�<��иrhU���Ȗ�;�Z�S����|~��q�k�Q6��x��ոH�m��a�QP���V�����'�5Ru0֤p�F5�u�/�K�g�]4Υbp�g9�P���8�	<BGq�
��Ց�%W�I�`�e��=��p�7v�`�(۫k���&q���҉�6{���O9/Ӣ�Q���r����O��8�����q�Xn��l���狇������nse��%�}9(�B��U���
GE�� �4�I�6����5��p��f�ˁ8P��ڂ�C<��5�C�D0��
RiQ;�l�����8�}6T#�D��T�,df?I��0�	��8�2V8��&֒�����L�1�"s[N�Ŵ(D9߮6ۢ|?.�|�]��e9[�7O�G��A-�E����;x� �.j�v-;�alℂ��u�y�|�1�{x��(gqr��ΚD�l������i��L�[���8��5WX��!}Bo�L�7r;�N츝��5|@hQ��܎xYr�'����X��tqK����M}.g��c}�t��xb�hm]�*B��I�c~�d�\��	��DF�%^i�0m��ز��ȩ����HD*��"�U�J�ύ�]��L���1�(��E�w�B�M�Ԣq�|�9���*>����m�i�]����,����A�{8���j=�i�� ���<F��ա��Y<�_s�s&� 5��5�/��[�{B�NB[���$�	E�_��u���Bz p�������)|���b�����-n����VC��+�� ���R�Ę0�a�n��v]�BHm��TCY�����,�T�V9/灸�e�銥��_`��߀�a��T�*
��^x�0I=U
��gއ��@��*���E�;�s��'��u㹐\.n�\��eY��\f��}�i�*n���b�Z?�7����3����{^AE���}ͼ\�f�A-o)��|���<��ʲ����yT�Fl�m%��<�N�}9�o2P͵����4Ak0{
���~D�MF�M��.��͵��t���t+��/Yy>L_P_SCea�y����*���
�̡�u�y��r�׃"�y�')�]��X|x�KL�5�up��=���1�3�0��:6����4O^���'���Z�!!����U��`�K[��ƀ|�Q��`";��׫�{~{-/�ޚ�2��z1_/�#:��V&�%z���0E.7�}��D��Ϛ+��ߘ3���@�%�M_0�,xf�r��j�y���,]#��e|y%����rU�8�Tu0�c�����`t�p�iK���?�¶��[��\���|Z�����<H� =W-�Q���|1�����<#�.͙;�˜I-r
B�(����Q����r�����9�;��U���N�B�|��\�r��f>���ӳ�CقJ�|GU�1��v��=���cq�`Ul�=�����E�F�s*L����/�g�>_�j'w���<�3�Q_�lfdA���
ܱ���|thV|���w�t��s�撚f� ��1mG�k�J�y��An��:�u5�u9[-��;�ժW�3ܞV�3�fO��b=�޼��Uo5      x   O   x���v
Q���W((M��L�+��I-Vs�	uV�0�QP/JM�,.I-JMQ(-N-R״��$���/1%73��� �R      v   �  x���Ko�0���YD�4v�F�iRȓ��@Pع�$�����������wt��q���r�m���DP0\��~j����6�ѿA�d(�Q�飇S��Q��t́.�p0k�v�C���J��.Ǝ��[Za6i���E�\nܣ?��ta#W�O�r%��~�8F�W0)N�s��Wo�?LM��2��-I�	���OPo�Cw?E�|9k�����'Zp�y�EGo>ь`7�V�?4Bͯ%�G�y�+�AApz�	H�W��5f9���-���E���5͙�eGg�@��ҿM�_2��p�g�$�P��c26�6l2[�l[7��[���)ät�;kK�C-=o�*�Oҹ�F�w��먺�G�=d��
��R�%�����a�(g�G���/���2���Իk���/���z>O�c�Z%���:�m`�z�]�     